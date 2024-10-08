<div class="full-width navBar">
    <div class="full-width navBar-options">
        <nav class="navBar-options-list">
            <ul class="list-unstyle">
                            <li class="noLink">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                        </span>
                        <input type="text" class="form-control" id="search" placeholder="Buscar cliente..." style="width: 500px; padding: 7px">
                    </div>
                    <div id="result-container" style="position: absolute; top: 100%; left: 0; width: 500px; z-index: 1000;">
                        <div id="result" style="border: 1px solid #ccc; background-color: #a0a0a0; color: #000000; max-height: 300px; overflow-y: auto;">
                        </div>
                    </div>
                </li>
                <i class="fas fa-exchange-alt fa-fw" id="btn-menu"></i>
                <li class="text-condensedLight noLink">
                    <a class="btn-exit" href="<?php echo APP_URL."logOut/"; ?>">
                        <i class="fas fa-power-off"></i>
                    </a>
                </li>
                <li class="text-condensedLight noLink">
                    <small><?php echo $_SESSION['usuario']; ?></small>
                </li>
                <li class="noLink">
                    <?php
                        if(is_file("./app/views/fotos/".$_SESSION['foto'])){
                            echo '<img class="is-rounded img-responsive" src="'.APP_URL.'app/views/fotos/'.$_SESSION['foto'].'">';
                        } else {
                            echo '<img class="is-rounded img-responsive" src="'.APP_URL.'app/views/fotos/default.png">';
                        }
                    ?>
                </li>
                
            </ul>
        </nav>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript" src="/sistemaredes/app/views/js/index.js"></script>
<div class="container">
    <div class="row">
        <div class="col-md-9" id="result">
        </div>
    </div>
</div>
