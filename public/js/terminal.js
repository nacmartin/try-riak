var tryRiak = (function () {
    var tr = {};
    tr.append = function(data) {
        $('#tutorial').append(data);
        this.scrollDown();
    }
    tr.submitCommand = function (url, header, riakdata, method) {
        var that = this;
        $.getJSON("/command", { url: url, method: method, header: header, data:riakdata }, function (data) {
            if (data.response !== undefined) {
                try {
                    var body = $.parseJSON(data.response);
                    output = '<pre>'+JSON.stringify(body, null, 4)+'</pre>';
                } catch(e) {
                    output = data.response.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
                }

                that.append('<div class="resbody">' + output + '</div><div class="header">'+data.header.replace(/\r/g,'<br>')+'</div>');
            } else if (data.error !== undefined) {
                that.append('<div class="error">' + data.error + '</div>');
            } else {
                that.append("Invalid response from TRY-RIAK server.", "error");
            }

        });
    };
    tr.next = function () {
        var that = this;
        $.get('/next', null, function(data) {
           $('#tutorial').append('<div class="step">'+data+'</step>');
           that.scrollDown();
        });
    }
    tr.prev = function () {
        var that = this;
        $.get('/prev', null, function(data) {
           $('#tutorial').append('<div class="step">'+data+'</step>');
           that.scrollDown();
        });
    }
    tr.scrollDown = function (){
        $("#tutorial").animate({ scrollTop: $("#tutorial").prop("scrollHeight") }, 500);
    };

    return tr;
}());

$(document).ready(function () {
  $("#terminal").focus();
  $("#run").click(function (event) {
      var url = $("#url").val();
      var method = $("input[name=method]:checked").val() || 'GET';
      var header = $("#header").val();
      var data = $("#data").val();

      tryRiak.submitCommand(url, header, data, method);
      return(false);
  });
  $("#next").click(function (event) {
      tryRiak.next();
  })
  $("#prev").click(function (event) {
      tryRiak.prev();
  })
});
