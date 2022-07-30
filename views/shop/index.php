    <div class="shopnav">
    	<a href="/shop/exist" class="button <?php if($exist){echo 'active';} ?>">NOT EXIST PRODUCTS</a>
        <a href="/shop/attributes" class="button <?php if($attr){echo 'active';} ?>">NOT EXIST ATTRIBUTES</a>
    	<a href="/shop/match" class="button <?php if($match){echo 'active';} ?>">NO MATCH PRODUCTS</a>
        <a href="/shop/syncAll" class="button <?php if($sync){echo 'active';} ?>">SYNCHRONIZATION</a>
        <a href="http://check.pashechko.kh.ua" class="button" style="color: red;" target="_blank">CHECK!</a>
    </div>
    <div class="shop-container">
        <?php if($exist): ?>
            <?php echo '<h3 class="report">'.count($shopList).' products is not exist</h3>'; ?>
            <h3 class="logs"><a href="/shop/unset/all"> Unset All </a></h3>
            <div>         
                <?php foreach($created as $product): ?>
                    <div class="created"><?php echo $product; ?><span> &raquo; <a href="/shop/unset/<?php echo $product; ?>"> created &#10006;</a></span></div>
                <?php endforeach; ?>                
            </div>        
            <div class="exist">    		
              <?php foreach($shopList as $product): ?>
               <div class="product"><?php echo $product; ?> &raquo; <a href="/shop/create/<?php echo $product; ?>">create this</a></div>
           <?php endforeach; ?>	    		
       </div>

       <?php elseif($match): ?> 
        <?php echo '<h3 class="report">'.$count.' products is no match</h3>'; ?>
        <div class="exist">           
            <?php foreach($shopList as $product): ?>
                <div class="product"><?php echo $product; ?> &raquo; <a href="/shop/sync/<?php echo $product; ?>">sync this</a></div>
            <?php endforeach; ?>                
        </div>       

    <?php elseif($sync): ?> 
    <!-- Синхронизация опционально START -->
    <form action="/shop/syncAll/" method="post">

        <label class="checkbox">
            <p>
                <input type="radio" name="option" value="price"><span> Цена </span>
            </p>
        </label>     
        <label class="checkbox">
            <p>
                <input type="radio" name="option" value="quantity"><span> Количество </span>
            </p>
        </label>
        <label class="checkbox">
            <p>
                <input type="radio" name="option" value="status"><span> Статус (вкл/выкл) </span>
            </p>
        </label>   
<!--         <label class="checkbox">
            <p>
                <input type="radio" name="option" value="name"><span> Наименование </span>
            </p>
        </label>                   
        <label class="checkbox">
            <p>
                <input type="radio" name="option" value="description"><span> Описание </span>
            </p>
        </label> -->        
        <hr>Result: <?php var_dump($result); ?></p>

        <input type="submit" name="submit" value="GO!" class="button submit">
    </form>
    <!-- Синхроизация опционально END -->
    <?php elseif($attr): ?> 
    <!--Позиции, где отсутствуют атрибуты START-->
    <h4><u>Всего <?php echo $count; ?> позиций</u></h4>
    <?php foreach ($list as $value): ?>
        <p><?php echo $value; ?></p>
    <?php endforeach; ?>    
    <?php endif; ?>
    <!--Позиции, где отсутствуют атрибуты END-->
</div>    
