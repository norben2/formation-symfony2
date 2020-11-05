/*on a accés au jquery, donc la je demande à JQuery de se brancher sur le bouton  qui a l'identifiant "ad_form_images",
     et quand on va cliquer dessus on va exécuter une fonction particulière que je définie ici même.
    la premi_re chose à faire dans cette fonction: récupérer le nombre di'mages il me suffit de
     connaitre combien de div form-groupe à l'intérieur de la div form_image qui sera stoqué dans index.
    la deuxième chose : récupérer le code du prototype, qui sera stoqué dans tmpl.
     */
    $("#add-form-image").click(function(){
        // je récupére le numéri des futures champs que je veux créer
        const index = +$('#widgets-counter').val();

        // je récupére le prototype des entrées
        const tmpl = $('#ad_form_images').data('prototype').replace(/__name__/g, index);

        //j'inject le code dans la div
        $('#ad_form_images').append(tmpl);

        $('#widgets-counter').val(index+1)
        
        handleDeleteButtons();
    });
    // gérer l'appuis sur les boutons de suppréssion
    function handleDeleteButtons(){
        /* je cherche tous les button qui ont un attribu data-action égale à delete
        je lui dis que quand clique dessus j'exécte la fonction que je défini ici même.
        */
        $('button[data-action="delete"]').click(function(){
            // je récupérer la target que je veux atteindre
            /* ic this représente le bouton sur lequel on clique, dataset représente tous les champs contenant data
            et target le champs parmi les data qui contient target*/
            const target = this.dataset.target;
            $(target).remove();
        });
    }

    /* chercher dans la div qui se nomme ad_form_images tout les divs qui ont la class 
    form-groupe */
    function updateCounter(){
        const count = +$('#ad_form_images div.form-groupe').length;
        $('#widgets-counter').val(count);
    }
    updateCounter();
    handleDeleteButtons();