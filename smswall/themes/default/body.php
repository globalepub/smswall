
<ul id="infos">
    <li><div class="highlight">Envoyer un message sur le mur :</div></li>
    <li><div class="line">Par Twitter avec le tag <span id="hashtag"><?php echo $config['hashtag']; ?></span></div></li>
    <li><div class="line">ou par SMS, au <span id="phone"><?php echo $config['phone_number']; ?></span></div></li>
</ul>

<div id="wrapper">
    <ul id="containerMsg"></ul>
</div>

<div id="overlayMsg" class="overlay" style="display: none;">
    <div id="bulleMsg"></div>
</div>

<div id="overlayMedia" class="overlay" style="display: none;">
    <div id="bulleMedia"></div>
</div>

<div id="footer"></div>

