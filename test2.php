<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Align Items Examples</title>
<style>
  .container {
    display: flex;
    border: 2px solid black;
    height: 200px;
    margin-bottom: 20px;
  }
  .item {
    width: 50px;
    background-color: lightblue;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid darkblue;
  }
  .baseline .item {
    font-size: 24px;
    line-height: 1.5;
  }
</style>
</head>
<body>

<h2>flex-start</h2>
<div class="container" style="align-items: flex-start;">
  <div class="item" style="height: 50px;">1</div>
  <div class="item" style="height: 100px;">2</div>
  <div class="item" style="height: 150px;">3</div>
</div>

<h2>flex-end</h2>
<div class="container" style="align-items: flex-end;">
  <div class="item" style="height: 50px;">1</div>
  <div class="item" style="height: 100px;">2</div>
  <div class="item" style="height: 150px;">3</div>
</div>

<h2>center</h2>
<div class="container" style="align-items: center;">
  <div class="item" style="height: 50px;">1</div>
  <div class="item" style="height: 100px;">2</div>
  <div class="item" style="height: 150px;">3</div>
</div>

<h2>stretch</h2>
<div class="container" style="align-items: stretch;">
  <div class="item">1</div>
  <div class="item">2</div>
  <div class="item">3</div>
</div>

<h2>baseline</h2>
<div class="container baseline" style="align-items: baseline;">
  <div class="item">1</div>
  <div class="item">2</div>
  <div class="item">3</div>
</div>

</body>
</html>
