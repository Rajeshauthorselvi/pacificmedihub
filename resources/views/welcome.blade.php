<!DOCTYPE html>
<html>
<head>
    <title>Comming Soon</title>
</head>
<style>
body, html {
  height: 100%;
  margin: 0;
}

.bgimg {
  background-image: url('./theme/images/background_image/26363.jpg');
  height: 100%;
  background-position: center;
  background-size: cover;
  position: relative;
  color: white;
  font-family: "Courier New", Courier, monospace;
  font-size: 25px;
}

.topleft {
  position: absolute;
  top: 0;
  left: 16px;
}

.bottomleft {
  position: absolute;
  bottom: 0;
  left: 16px;
}

.middle {
  position: absolute;
  top: 50%;
  left: 20%;
  transform: translate(-50%, -50%);
  text-align: center;
}

.middle .login-btn{
  padding: 10px;
  border-radius: 3px;
  background-color: #67b7aa;
  color:#fff;
  text-decoration: none;
  border: 1px solid #fff;
}
.middle .login-btn:hover {
    background-color: #fff;
    color: #67b7aa;
}
hr {
  margin: auto;
  width: 40%;
}

</style>
<body>

<div class="bgimg">

  <div class="middle">
    <h1>COMING SOON</h1>
    <a class="login-btn" href="{{ url('/who-you-are') }}">GoTo LOGIN</a>
  </div>

</div>

</body>
</html>
