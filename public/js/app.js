let generate = document.querySelector('form');

generate.addEventListener('submit', (e) => {
    e.preventDefault();
    let length = e.target[0].value;
    let many = e.target[1].value;
    let int = e.target[2].value;
    let upper = e.target[3].value;
    let symbol = e.target[4].value;

    let url = "https://password-generator.menezes.be/api/v1/generate?"
    +"length="+length
    +"&many="+many
    +"&int="+int
    +"&upper="+upper
    +"&symbol="+symbol;

    fetch(url)
    .then((response) => { return response.json(); })
    .then(response => { 

        let content = document.querySelector(".mdp_content");

        if (response.success === true) {
            content.innerHTML = Object.keys(response.data).map(function(key) {
            return [' Mot de passe ' + key + ':<br>' + response.data[key]];
            }).join("<br><br>");
        } else {
            content.innerHTML = response.erreur.message;
        }

        let modal = document.querySelector("#modal");
        let span = document.getElementsByClassName("close")[0];

        modal.style.display = "flex";

        span.onclick = function() {
          modal.style.display = "none";
        }

        window.onclick = function(event) {
          if (event.target == modal) {
            modal.style.display = "none";
          }
        }
    }).catch(function(error) {
      console.log('Il y a eu un problème');
    });;
});