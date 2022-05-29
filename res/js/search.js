function order_by() {
    let value = document.querySelector("select#order-by").value;
    let form = document.querySelector("#search-form");
    let input = document.createElement("input");
    input.type = "hidden";
    input.name = "order-by";
    input.value = value;
    form.appendChild(input);
    form.submit();
}//prend la valeur de la fonction "trier par" et l'inclut dans le formulaire de recherche 
