<div class="manual">
  <p>1С->Справочники->Номенклатура->Печать прайс-листа</p>
  <p>Тип цены выбираем: Сайт 2019 (наличка) и идем в Настройка->Поля</p>
  <p>Удаляем все поля и добавляем "Артикул"(Номенклатура->Артикул)</p>
  <p>Сгенерированный документ сохраняем как [name].xlsx</p>
  <p>Создаем пустой файл list.xlsx и вставляем в него два столбца из только что сохраненного [name].xlsx: атикулы и цены. После чего удаляем ненужные верхние ячейки</p>
  <p>Проверяем столбец артикулов, чтобы в нем не было букв, но только цифры. Выбираем для столбца цен формат ячеек "Общий". Ctrl+S</p>
  <p>Сохраняем как CSV(разделители запятые). Сохраненный файл list.csv должен открываться в блокноте.</p>
  <p>Загружаем файл list.csv в форму</p>
</div>
<?php if(empty($_SESSION['org'])): ?>
    <section>
<div class="form">
    <form action="/org/upload" method="post" enctype="multipart/form-data">
  <div class="forceColor"></div>
  <div class="topbar">
    <div class="spanColor"></div>
    <input type="file" class="input" name="file"/>
  </div>
  <input type="submit" name="submit" id="submit" class="submit" value="Загрузить">
</div>
    <form/>
    </section>

    <?php elseif(isset($_SESSION['org']['upload'])): ?><!---------------------------------------------------------------------------------------->

<section>
        <div class="form" style="top: 50px; margin-bottom: 1%;">
            <h2 style="color: #080808;">Массив получен</h2>
            <hr/>
            <br/>
    <form action="/org/update" method="post">
  <div class="forceColor"></div>
  <div class="topbar">
    <div class="spanColor"></div>
    <?php foreach($list as $item): ?>
    <input type="text" class="input" style="background: rgba(60, 60, 60, 0.9);" value="<?php echo 'Артикул: '.$item[0].' || Цена: '.$item['1']; ?>" />
<?php endforeach; ?>
  </div>
  <input type="submit" name="submit" class="submit update" value="Обновить данные">
</div>
    <form/>
    </section>

    <?php elseif(isset($_SESSION['org']['update'])): ?><!---------------------------------------------------------------------------------------->

    <section>
        <div class="form" style="top: 60px; margin-bottom: 1%;">
        <h2 style="color: #080808;">Данные обновлены</h2>
        <br/>
        <hr/>
        <br/>
  <div class="forceColor"></div>
  <div class="topbar">
    <div class="spanColor"></div>
    <?php foreach($list as $item): ?>

    <input type="text" class="input" style="background: rgba(60, 60, 60, 0.9);" value="<?php echo 'Артикул: '.$item['sku'].' || Цена: '.$item['price']; ?>" />
<?php endforeach; ?>
  </div>
  <a href="/org/">
  <input type="button" name="submit" class="submit back" value="Выход" >
  </a>
</div>
    <!-- <form/> -->
    </section>

    <?php endif; ?><!---------------------------------------------------------------------------------------->