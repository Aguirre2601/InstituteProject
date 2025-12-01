
<?php
// Bloque PHP al inicio de la Vista para preparar los datos
$toast_message = '';
$toast_class = ''; // text-bg-danger, text-bg-success

if (isset($_SESSION['error'])) {
    $toast_message = $_SESSION['error'];
    $toast_class = 'text-bg-danger'; 
    unset($_SESSION['error']);
} elseif (isset($_SESSION['mensaje'])) {
    $toast_message = $_SESSION['mensaje'];
    $toast_class = 'text-bg-success'; 
    unset($_SESSION['mensaje']);
}elseif (isset($_SESSION['warning'])) {
    $toast_message = $_SESSION['warning'];
    $toast_class = 'text-bg-warning'; 
    unset($_SESSION['warning']);
} elseif (isset($_SESSION['info'])) {
    $toast_message = $_SESSION['info'];
    $toast_class = 'text-bg-info'; 
    unset($_SESSION['info']);
}
?>

<?php if ($toast_message): ?>
<script>
    // Se ejecuta inmediatamente después de la definición de la variable
    // Esto es solo para transferir los datos del PHP al JS global
    window.sessionToastData = {
        message: '<?= addslashes($toast_message) ?>',
        class: '<?= $toast_class ?>'
    };
</script>
<?php endif; ?>
