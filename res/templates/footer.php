<?php
global $USER_INFO;
?>

<footer>
    <div class="footer">
        <div class="up" onclick="scroll_to_top();">Retour en haut</div>
        <!-- "onclick" appelle une fonction JS pour scroll en haut de la page -->
        <div class="footer-content">
            <div class="help">
                <h1 style="text-align: center">Besoin d'aide ?</h1>
                <h3 style="text-align: center">Il n'y a pas de question bête !</h3>
                <h4 style="text-align: center; font-weight: normal">Nos conseillers ont réponse à tout, enfin presque. <br />
                    Vous pouvez ainsi les contacter ici.<br />  
                    Ils vous répondront le plus vite possible. 
                </h4>
                <form id="assistance-form" method="POST" action="<?= $PATH ?>/ask-for-assistance.php" onsubmit="return check_assistance_form();">
                    <label for="questions"></label>
                    <textarea name="questions" id="questions"></textarea> 
                    <br />
                    <span style="display: block; text-align: center;">
                        <label for="email-address">Votre adresse e-mail :</label>
                        <input type="text" id="email-address" name="email-address" style="width: 200px;" value="<?= (isset($USER_INFO)) ? $USER_INFO["email_address"] : "" ?>">
                    </span>
                    <br />
                    <button class="buttonls" style="display: block; margin: auto" type="submit">Envoyez</button>
                    
                </form>
            </div>
            
        </div>
    </div> 
</footer>

<script type="text/javascript" src="<?= res("res/js/common.js") ?>"></script>
