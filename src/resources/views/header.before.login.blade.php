<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
<link 
   rel="stylesheet"
   href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
   integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous"
>
  <title>やんばるQiita</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
<style type="text/css">

header{
height: 55px;
width: 100%;
background-color: #00CC66;
}
.logo{
  padding-bottom: 20px;
}
.container{
width: 1000px;
padding: 0 15px;
margin: 0 auto;
}
.header-left{
float:left;
}
.header-right{ 
float:right;
}

ul.menu li {
float: left;
display: flex;
justify-content: flex-end;
align-items: center;
list-style: none;
margin: 10px;
}

</style>

</head>

<body>
<header class="navbar navbar-dark">    
     <div class="logo">
     <a class="navbar-brand" href="{{url('')}}" class="nav-link">やんばるQiita
     </a>
     </div> 
     
     <div class="header-list">
       <ul class="navbar-nav">
       <ul class="menu">  
          <li class="nav-item"><a href="{{url('')}}" class="nav-link">ログイン</a></li> 
          <li class="nav-item"><a href="{{url('')}}" class="nav-link">ユーザー登録</a></li>
       </ul>
       </ul>
     </div>
  </header>
</body>
</html>