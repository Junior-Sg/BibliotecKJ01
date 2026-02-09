
    <!-- BUSCADOR -->
    <div class="buscador-wrap">
        <form action="index.php" method="get">
            <input type="hidden" name="controller" value="Libro">
            <input type="hidden" name="action" value="buscar">
            <input id="searchInput" class="buscador-input" type="text" name="q" placeholder="Buscar por título o autor...">
            <button id="searchButton" class="buscador-btn" disabled>Buscar</button>
        </form>
    </div>
<style>

</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');

    searchInput.addEventListener('input', function() {
        searchButton.disabled = this.value.trim() === '';
    });
});
</script>