// Confirmación de borrado ya está inline en onclick del link.
// Acá manejamos el submit vacío del form de filtros (que no ensucie la URL).

document.addEventListener('DOMContentLoaded', function () {
  var form = document.querySelector('.admin-filtros');
  if (!form) return;

  form.addEventListener('submit', function (e) {
    // Si todos los campos están vacíos, recarga limpia
    var inputs = form.querySelectorAll('input[type="text"], select');
    var todosVacios = Array.from(inputs).every(function (el) {
      return el.value.trim() === '';
    });

    if (todosVacios) {
      e.preventDefault();
      window.location.href = 'dashboard.php';
    }
  });
});
