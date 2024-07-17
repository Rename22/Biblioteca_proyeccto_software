(function(document) {
    'buscador';

    var LightTableFilter = (function(Arr) {

      var _input;

      function _onInputEvent(e) {
        _input = e.target;
        var tables = document.getElementsByClassName(_input.getAttribute('data-table'));
        Arr.forEach.call(tables, function(table) {
          Arr.forEach.call(table.tBodies, function(tbody) {
            Arr.forEach.call(tbody.rows, _filter);
          });
        });
      }

      function _filter(row) {
        var text = row.textContent.toLowerCase(), val = _input.value.toLowerCase();
        row.style.display = text.indexOf(val) === -1 ? 'none' : 'table-row';
      }

      return {
        init: function() {
          var inputs = document.getElementsByClassName('light-table-filter');
          Arr.forEach.call(inputs, function(input) {
            input.oninput = _onInputEvent;
          });
        }
      };
    })(Array.prototype);

    document.addEventListener('readystatechange', function() {
      if (document.readyState === 'complete') {
        LightTableFilter.init();
      }
    });

})(document);


document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById("searchInput");
    const tableRows = document.querySelectorAll("#usuariosTable tr");

    searchInput.addEventListener("keyup", function() {
        const searchText = searchInput.value.toLowerCase();
        tableRows.forEach(function(row) {
            const rowText = row.textContent.toLowerCase();
            if (rowText.indexOf(searchText) > -1) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
});
$(document).ready(function() {
    // Función de búsqueda en tiempo real
    $("#searchInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#usuariosTable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
});


