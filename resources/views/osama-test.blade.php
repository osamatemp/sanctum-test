<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TEST AUTH</title>
</head>
<body>
<button onclick="login()">login</button>
<button onclick="logout()">logout</button>
<button onclick="$cookie()">cookie</button>
<button onclick="checkAuth()">check auth</button>
<button onclick="checkAuthPost()">check auth post</button>
</body>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>

    axios.defaults.withCredentials = true;

    function $cookie()
    {
        axios({
            url: '/sanctum/csrf-cookie',
            method: 'get',
            headers: {
                'Accept': 'application/json',
            },
        })
            .then(response => {
                console.log(response.config.headers['X-XSRF-TOKEN']);
            });
    }

    function login()
    {
        axios({
            url: '/sanctum/csrf-cookie',
            method: 'get',
            headers: {
                'Accept': 'application/json',
            },
        }).then(response => {
            console.log(response);

            axios({
                url: '/api/login',
                method: 'post',
                headers: {
                    'Accept': 'application/json',
                },
                data: {
                    email: 'osama@akre.com',
                    password: '11111111',
                    remember: true
                }
            }).then((res) => {
                console.log(res.data.user.email);
            }).catch(err => console.log(err))
        });

    }

    function logout()
    {
        axios({
            url: '/api/logout',
            method: 'post',
            headers: {
                'Accept': 'application/json',
            },
        }).then((res) => {
            console.log(res);
        });
    }

    function checkAuth()
    {
        axios({
            url: '/api/auth-check',
            method: 'get',
            headers: {
                'Accept': 'application/json',
            },
        }).then((res) => {
            console.log(res);
        });
    }

    function checkAuthPost()
    {
        axios({
            url: '/api/auth-check-p',
            method: 'post',
            headers: {
                'Accept': 'application/json',
            },
        }).then((res) => {
            console.log(res);
        });
    }

</script>
</html>
