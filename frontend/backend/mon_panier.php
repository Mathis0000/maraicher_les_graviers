<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passer une commande</title>
    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Bootstrap DateTimePicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tempusdominus-bootstrap-4@5.39.0/build/css/tempusdominus-bootstrap-4.min.css">
    <!-- Include French locale for Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js"></script>
</head>
<body>
    <h1>Passer une commande</h1>
    <form action="traitement_commande.php" method="post">
        <div class="form-group">
            <label for="date_commande">Date de la commande :</label>
            <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" id="date_commande" name="date_commande" data-target="#datetimepicker" required>
                <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                </div>
            </div>
            <small class="form-text text-muted">Veuillez sélectionner une date et une heure pour votre commande.</small>
        </div>

        <button type="submit" class="btn btn-primary">Valider la commande</button>
    </form>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tempusdominus-bootstrap-4@5.39.0/build/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Initialisation du DateTimePicker -->
    <script>
        $(function () {
            // Définir la langue de moment.js sur le français
            moment.locale('fr');
            
            // Inclure la localisation française pour Bootstrap DateTimePicker
            $.fn.datetimepicker.Constructor.Default = $.extend({}, $.fn.datetimepicker.Constructor.Default, {
                locale: 'fr'
            });

            $('#datetimepicker').datetimepicker({
                format: 'YYYY-MM-DD HH:mm', // Format de date et heure
                minDate: moment() // Date minimale (aujourd'hui)
            });
        });
    </script>
</body>
</html>
