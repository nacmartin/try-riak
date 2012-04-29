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
                    var output = '';
                    for (prop in body) {
                        output += prop + ': ' + body[prop]+', <br/>';
                    }
                } catch(e) {
                    output = data.response;
                }

                that.append(output + '<div class="header">'+data.header.replace(/\r/g,'<br>')+'</div>');
            } else if (data.error !== undefined) {
                that.append(data.error, "error", "", true);
            } else {
                that.append("Invalid response from TRY-RIAK server.", "error");
            }

        });
    };
    tr.next = function () {
        var that = this;
        $.get('/next', null, function(data) {
           $('#tutorial').append(data);
           that.scrollDown();
        });
    }
    tr.prev = function () {
        var that = this;
        $.get('/prev', null, function(data) {
           $('#tutorial').append(data);
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
      var method = $("#method").val();
      var header = $("#header").val();
      var data = $("#data").val();

      tryRiak.submitCommand(url, header, data, method);
  });
  $("#next").click(function (event) {
      tryRiak.next();
  })
  $("#prev").click(function (event) {
      tryRiak.prev();
  })
});
