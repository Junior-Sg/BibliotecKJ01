document.addEventListener('DOMContentLoaded', () => {

  const modal = document.getElementById('modalDetalle');
  const baseUrl = window.BASE_URL; 

  let libroActual = null;

  modal.addEventListener('show.bs.modal', async (event) => {
    const button = event.relatedTarget;
    const id = button.getAttribute('data-id');

    try {
      const resp = await fetch(`${baseUrl}/index.php?controller=Libro&action=detalleJson&id=${id}`);
      const json = await resp.json();

      if (!json.ok) throw new Error(json.error || 'Error al obtener detalle');

      const d = json.data;
      libroActual = d;

      libroActual.id_libro = parseInt(id);

      const imgPath = d.Imagen
        ? `${baseUrl}/public/img/Libros/${d.Imagen}`
        : `${baseUrl}/public/img/Libros/default.jpg`;

      document.getElementById('det_imagen').src = imgPath;
      document.getElementById('det_titulo').textContent = d.titulo;
      document.getElementById('det_sinopsis').textContent = d.sinopsis || 'Sin sinopsis disponible.';
      document.getElementById('det_autores').textContent = (d.autores || []).join(', ') || 'Desconocido';
      document.getElementById('det_generos').textContent = (d.generos || []).join(', ') || 'Sin género';
      document.getElementById('det_editorial').textContent = d.editorial || '';
      document.getElementById('det_anio').textContent = d.año_publicacion || '';
      document.getElementById('det_estante').textContent = d.Estante || '';
      document.getElementById('det_disp').textContent = d.disponibilidad ?? 0;

      window.libroActual = libroActual;

    } catch (e) {
      console.error(e);
      alert('No se pudo cargar el detalle del libro.');
    }
  });
});
