<!DOCTYPE html>
<html>
<head>
    <title>EKAPTA</title>
</head>
<body>
<h1><?php echo e($details['title']); ?></h1>

<?php echo nl2br($details['message']); ?>


<p>
    Terima kasih,
    <br>
    
    <a href="<?php echo e(URL::to('/')); ?>"><?php echo e(URL::to('')); ?></a>
    <br><br>
    Pesan ini terkirim secara otomatis, tidak perlu dibalas.
</p>
</body>
</html>
<?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/emails/notification.blade.php ENDPATH**/ ?>