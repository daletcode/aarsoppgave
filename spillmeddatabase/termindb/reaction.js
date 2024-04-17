// Selektorer for HTML-elementer
const mainMenu = document.querySelector(".main-menu"); // Henter hovedmeny-elementet
const clickableArea = document.querySelector(".clickable-area"); // Henter klikkbart område-elementet
const message = document.querySelector(".clickable-area .message"); // Henter meldings-elementet i det klikkbare området
const endScreen = document.querySelector(".end-screen"); // Henter sluttskjerm-elementet
const reactionTimeText = document.querySelector(".end-screen .reaction-time-text"); // Henter reaksjonstidsmeldings-elementet i sluttskjermen
const playAgainBtn = document.querySelector(".end-screen .play-again-btn"); // Henter "Play Again"-knapp-elementet
const form = document.getElementById("form"); // Henter skjema-elementet
const score = document.getElementById("score"); // Henter poeng-elementet

// Variabler for spilllogikk og tidtaking
let timer; // Variabel for tidtaker
let greenDisplayed; // Variabel som indikerer om grønn farge er vist
let timeNow; // Variabel for nåværende tidspunkt
let waitingForStart; // Variabel som indikerer om programmet venter på start
let waitingForGreen; // Variabel som indikerer om programmet venter på grønn farge
let scores; // Variabel for lagring av poeng

// Initialiserer variabler ved start av programmet
const init = () => {
    greenDisplayed = false;
    waitingForStart = false;
    waitingForGreen = false;
    scores = [];
}

init();


const setGreenColor = () => {
    clickableArea.style.backgroundColor = "#32cd32";
    message.innerHTML = "Click Now!";
    message.style.color = "#111";
    greenDisplayed = true;

    // Lagrer øyeblikket funksjonen ble kalt ved å oppdatere verdien til variabelen 'timeNow'.
    timeNow = Date.now();
};

// Starter spillet ved å endre bakgrunnsfargen og sette opp en timer for å vise grønn farge
const startGame = () => {
    clickableArea.style.backgroundColor = "#c1121f";
    message.innerHTML = "Wait for the green Color";
    message.style.color = "#fff";
    let randomNumber = Math.floor(Math.random() * 2000 + 3000);
    timer = setTimeout(setGreenColor, randomNumber);
    waitingForStart = false;
    waitingForGreen = true;
};

// Legger til en hendelseslytter for å starte spillet når hovedmenyen klikkes
mainMenu.addEventListener("click", () => {
    mainMenu.classList.remove("active");
    startGame();
});


// Denne funksjonen kalles når spillet avsluttes
const endGame = () => {
    // Legg til "active" klassen på sluttskjermen for å vise den
    endScreen.classList.add("active");
    // Stopp timeren ved å rydde opp i setTimeout-funksjonen
    clearTimeout(timer);
    // Initialiser en variabel for å beregne den totale poengsummen
    let total = 0;
    // Iterer gjennom hvert element i scores-arrayet og legg til i total
    scores.forEach((s) => {
        total += s;
    });
    // Beregn gjennomsnittspoengsummen og rund av til nærmeste hele tall
    let averageScore = Math.round(total / scores.length);
    // Oppdater score-elementet med gjennomsnittspoengsummen
    score.value = averageScore;
    // Oppdater HTML-innholdet til reaksjonsteksten med gjennomsnittspoengsummen
    reactionTimeText.innerHTML = `${averageScore} ms`;
    // Opprett et dataobjekt med gjennomsnittspoengsummen
    const data = {
        score: averageScore
    };

    // Send en POST-request til "login/save_score.php" med JSON-formatert data
    fetch("login/save_score.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    });
};


// Viser reaksjonstiden på skjermen
const displayReactionTime = (rt) => {
    clickableArea.style.backgroundColor = "#faf0ca";
    message.innerHTML = "<div class='reaction-time-text'>"+rt+" ms</div>Click to continue.";
    greenDisplayed = false;
    waitingForStart = true;
    scores.push(rt);
    if (scores.length >= 3) {
        endGame();
    }
};

// Viser melding om at brukeren klikket for tidlig
const displayTooSoon = () => {
    clickableArea.style.backgroundColor = "#faf0ca";
    message.innerHTML = "Too Soon. Click to continue";
    message.style.color = "#111";
    waitingForStart = true;
    clearTimeout(timer);
};

// Legg til en klikkshendelse på clickableArea-elementet
clickableArea.addEventListener("click", () => {

    // Sjekk om det grønne elementet allerede vises
    if (greenDisplayed) {
        // Beregn reaksjonstiden når det grønne er klikket
        let clickTime = Date.now();
        let reactionTime = clickTime - timeNow;
        // Vis reaksjonstiden og logg den til konsollen
        displayReactionTime(reactionTime);
        console.log(reactionTime);
        return;
    }
    // Sjekk om spillet venter på å starte
    if (waitingForStart) {
        // Start spillet når klikket skjer før det grønne elementet vises
        startGame();
        return;
    }
    // Hvis det grønne elementet ikke vises ennå, vis "for tidlig" melding
    if (waitingForGreen) {
        displayTooSoon();
    }
});


// Legger til hendelseslytter for klikk på "Play Again" knappen
playAgainBtn.addEventListener("click", () => {
    endScreen.classList.remove("active");
    init();
    startGame();
});
