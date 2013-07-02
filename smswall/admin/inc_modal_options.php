<div id="bulleOptions" class="bulle" style="display: none;">

    <div id="closerOptions">
        <img src="../media/closer.png" width="48" height="48" />
    </div>

    <?php
    $userState = $tagState = '';
    if($config['userstream'] == 1){
        $userState = 'active btn-success';
        $hastagBlocState = 'display: none;';
    }else{
        $tagState = 'active btn-success';
        $hastagBlocState = '';
    }
    ?>

    <div id="userstream_bloc">
        <div class="title">Choix du type de wall :</div>
        <form id="userForm" class="form-inline">
            <input id="channelForm" type="hidden" name="channelForm" value="<?php echo $config['channel_id']; ?>" />
            <div id="userstream" class="btn-group" data-toggle="buttons-radio">
                <button type="button" value="user" class="btn btn-small <?php echo $userState; ?>">Userstream</button>
                <button type="button" value="tag" class="btn btn-small <?php echo $tagState; ?>">Hashtag</button>
            </div>
            <span class="label label-success hide"><i class="icon-ok icon-white"></i></span>
        </form>
    </div>

    <div id="hashtag_bloc" style="<?php echo $hastagBlocState; ?>">
        <div class="title">Hashtag / RSS :</div>
        <div class="subTitle">
            Faites précéder les tags du #<br/>
            Tags multiples : séparés par une virgule.
        </div>
        <form id="hashForm" class="form-inline">
            <div class="input-append">
                <input class="span3" id="hashtagForm" type="text" name="hashtagForm" value="<?php echo utf8_encode($config['hashtag']); ?>">
                <button id="submitHash" class="btn" type="submit">Modifier</button>
            </div>
            <span class="label label-success hide"><i class="icon-ok icon-white"></i></span>
        </form>
    </div>

    <div class="title">Modération :</div>
    <div class="subTitle">
        Choisissez le type de modération de votre mur
    </div>
    <form id="modoForm" class="form-inline">
        <?php
        $aprioriState = $aposterioriState = '';
        if($config['modo_type'] == 1){
            $aposterioriState = 'active btn-success';
        }else{
            $aprioriState = 'active btn-danger';
        }
        ?>
        <div id="modo" class="btn-group" data-toggle="buttons-radio">
            <button type="button" value="pri" class="btn btn-small <?php echo $aprioriState; ?>">A priori</button>
            <button type="button" value="pos" class="btn btn-small <?php echo $aposterioriState; ?>">A posteriori</button>
        </div>
        <span class="label label-success hide"><i class="icon-ok icon-white"></i></span>
    </form>

    <div class="title">Filtrage des retweets (RT) :</div>
    <form id="retweetForm" class="form-inline">
        <div class="subTitle">Filtrage des RT: pour les nouveaux messages seulement. Attention, actuellement les RT sont sauvés en base</div>
        <?php
        $showRtState = $hideRtState = '';
        if($config['retweet'] == 1){
            $showRtState = 'active btn-success';
        }else{
            $hideRtState = 'active btn-danger';
        }
        ?>
        <div id="retweet" class="btn-group" data-toggle="buttons-radio">
            <button type="button" value="hide" class="btn btn-small <?php echo $hideRtState; ?>">Masquer</button>
            <button type="button" value="show" class="btn btn-small <?php echo $showRtState; ?>">Afficher</button>
        </div>
        <span class="label label-success hide"><i class="icon-ok icon-white"></i></span>
    </form>


    <div class="title">Affichage avatars :</div>
    <form id="avatarForm" action="update_config.php" method="post">
        <?php
        $masquerState = $afficherState = '';
        if($config['avatar'] == 1){
            $afficherState = 'active btn-success';
        }else{
            $masquerState = 'active btn-danger';
        }
        ?>
        <div id="avatar" class="btn-group" data-toggle="buttons-radio">
            <button type="button" value="hide" class="btn btn-small <?php echo $masquerState; ?>">Masquer</button>
            <button type="button" value="show" class="btn btn-small <?php echo $afficherState; ?>">Afficher</button>
        </div>
        <span class="label label-success hide"><i class="icon-ok icon-white"></i></span>
    </form>


    <div class="title">Thèmes :</div>
    <form id="themeForm" class="form-inline">
        <?php
        $dir = "../themes";
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                $directories = array();
                while (($file = readdir($dh)) !== false) {
                    if($file != "." && $file != ".."){
                        $directories[] = $file;
                    }
                }
                closedir($dh);
            }
        }
        ?>
        <select id="theme" name="theme" class="span3">
            <?php
            foreach($directories as $theme){
                $selected = ($config['theme'] == $theme) ? 'selected="selected" ' : '';
                ?>
                <option <?php echo $selected; ?>><?php echo $theme; ?></option>
                <?php
            }?>
        </select>
        <span class="label label-success hide"><i class="icon-ok icon-white"></i></span>
    </form>

</div>
