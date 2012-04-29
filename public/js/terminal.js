var tryRiak = (function () {
    var tr = {};
    tr.submitCommand = function (url, header, riakdata, method) {
        jQuery.getJSON("command", { url: url, method: method, header: header, data:riakdata }, function (data) {
            alert("hooo");
        //    if (data.response !== undefined) {
        //        append(JSON.stringify(data.response), "response");
        //    } else if (data.error !== undefined) {
        //        append(data.error, "error", "", true);
        //    } else if (data.notification !== undefined) {
        //        append(data.notification, "notification", "", true);
        //    } else {
        //        append("Invalid response from TRY-RIAK server.", "error");
        //    }

        });
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
      return false;
  });
});
