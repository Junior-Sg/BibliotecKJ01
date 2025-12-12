<?php
// partial: busqueda.php
?>
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
 /* BUSCADOR */
        .buscador-wrap {
            margin: 20px auto;
            max-width: 700px;
            position: relative;
        }
        .buscador-input {
            width: 100%;
            padding: 14px 20px;
            border-radius: 40px;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .buscador-btn {
            position: absolute;
            right: 6px;
            top: 6px;
            bottom: 6px;
            padding: 4px 18px;
            border-radius: 40px;
            background:#8b6f57;
            color:#fff;
            border:none;
        }
        .buscador-btn:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
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