<!DOCTYPE html>
<html>
<head>
    <title>Contact Laravel 8 CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.0/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.0/js/dataTables.bootstrap5.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.0/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <style media="screen">
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #F8FAFB;
        }

        .custom-container {
            width: 1600px;
        }

        .col-md-9 {
            padding: 25px;
            margin: 25px;
        }

        .glow-table {
            box-shadow: 0 2px 10px rgba(34, 144, 248);
        }

        @media (min-width: 1920px) {
            .container {
                max-width: 1080px;
            }
        }

        .add-new-button {
            float: right;
            margin-bottom: 15px;
        }

        .cart-icon {
            position: absolute;
            display: inline-block;
        }

        .cart-icon i {
            font-size: 24px;
        }

        .cart-count {
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #fcfcfc;
            font-family: sans-serif;
        }

        footer {
            position: relative;
            bottom: 0;
            left: 0;
            right: 0;
            background: #3e5686;
            height: auto;
            width: 100%;
            margin-top: 550px;
            padding-top: 40px;
            color: #fff;
            background-image: url('{{ asset('acmsirotate.gif') }}');
            background-repeat: no-repeat;
            background-size: cover;
            background-size: 7%;
        }

        .footer-content {
            display: relative;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
        }

        .footer-content h3 {
            font-size: 2.1rem;
            font-weight: 500;
            text-transform: capitalize;
            line-height: 3rem;
        }

        .footer-content p {
            max-width: 500px;
            margin: 10px auto;
            line-height: 28px;
            font-size: 14px;
            color: #cacdd2;
        }

        .socials {
            list-style: none;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 1rem 0 3rem 0;
        }

        .socials li {
            margin: 0 10px;
        }

        .socials a {
            text-decoration: none;
            color: #fff;
            border: 1.1px solid white;
            padding: 5px;
            border-radius: 50%;
        }

        .socials a i {
            font-size: 1.1rem;
            width: 20px;
            transition: color .4s ease;
        }

        .socials a:hover i {
            color: aqua;
        }

        .footer-bottom {
            background: #f5c467;
            width: 100vw;
            padding: 20px;
            padding-bottom: 40px;
            text-align: center;
        }

        .footer-bottom p {
            float: left;
            font-size: 14px;
            word-spacing: 2px;
            text-transform: capitalize;
        }

        .footer-bottom p a {
            color: #44bae8;
            font-size: 16px;
            text-decoration: none;
        }

        .footer-bottom span {
            text-transform: uppercase;
            opacity: .4;
            font-weight: 200;
        }

        .footer-menu {
            float: right;
        }

        .footer-menu ul {
            display: flex;
        }

        .footer-menu ul li {
            padding-right: 10px;
            display: block;
        }

        .footer-menu ul li a {
            color: #cfd2d6;
            text-decoration: none;
        }

        .footer-menu ul li a:hover {
            color: #27bcda;
        }

        @media (max-width: 500px) {
            .footer-menu ul {
                display: flex;
                margin-top: 10px;
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        @yield('content')
    </div>

    <footer>
        <div class="footer-content">
            <h3>Annapolis IT</h3>
            <p>Template.</p>
        </div>
        <div class="footer-bottom">
            <p>copyright &copy; <a href="https://github.com/jangiethegreat">Annapolis IT Developer Team</a></p>
        </div>
    </footer>
</body>
</html>
