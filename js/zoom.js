let fotos = [];
let indiceActual = 0;
let startX = 0;

document.addEventListener("DOMContentLoaded", () => {
  fotos = Array.from(document.querySelectorAll(".galeria img"))
    .map(img => img.src);

  const zoomImg = document.getElementById("zoom-img");

  // Touch start
  zoomImg.addEventListener("touchstart", e => {
    startX = e.touches[0].clientX;
  }, { passive: true });

  // Touch end
  zoomImg.addEventListener("touchend", e => {
    const endX = e.changedTouches[0].clientX;
    const diff = startX - endX;

    if (Math.abs(diff) > 50) {
      diff > 0 ? siguiente() : anterior();
    }
  }, { passive: true });
});

function abrirZoom(i) {
  indiceActual = i;
  document.getElementById("zoom").style.display = "flex";
  mostrarImagen();
}

function cerrarZoom() {
  document.getElementById("zoom").style.display = "none";
}

function siguiente() {
  indiceActual = (indiceActual + 1) % fotos.length;
  mostrarImagen();
}

function anterior() {
  indiceActual = (indiceActual - 1 + fotos.length) % fotos.length;
  mostrarImagen();
}

function mostrarImagen() {
  document.getElementById("zoom-img").src = fotos[indiceActual];
}
