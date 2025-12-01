    </main> 
    <footer style="text-align: center; padding: 20px; border-top: 1px solid #ccc; margin-top: 20px;">
            <p>&copy; <?= date('Y') ?> Instituto 93 - Gestión Educativa</p>
    </footer>

    <?php if (isset($scripts) && is_array($scripts)): ?>
        <?php foreach ($scripts as $script): ?>
            <script src="/assets/js/<?= $script ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>

    <script src="/assets/js/main.js"></script>
    <script src="/assets/js/resizable-columns.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>



</body>
</html>