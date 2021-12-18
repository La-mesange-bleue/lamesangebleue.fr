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
                    Ils vous répondreront le plus vite possible. 
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
                    <button style="display: block; margin: auto" type="submit">Envoyez</button>
                    
                </form>
            </div>
            <div style="display: flex">
                <div class="knowusbetter">
                    <h3>Pour mieux nous connaitre</h3>
                    <ul>
                        <li><h4><a href="#">Notre concept</a></h4></li>
                        <li><h4><a href="#">Durabilité</a></h4></li>
                        <li><h4><a href="#">Questions/Réponses</a></h4></li>
                    </ul>
                </div>
                <div class="services-products">
                    <h3>Services, produits</h3>
                    <ul class="services">
                        <li><h4><a href="#">Garanties</a></h4></li>
                        <li><h4><a href="#">Paiement sécurisé</a></h4></li>
                        <li><h4><a href="#">Livraison</a></h4></li>
                    </ul>
                </div>
            </div>
        </div>
    </div> 
</footer>

<script type="text/javascript" src="<?= res("res/js/common.js") ?>"></script>
