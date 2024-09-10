// Funktion för att visa videor
function showVideo(youtubeLink) {
    document.getElementById('video-container').style.display = 'block';
    document.getElementById('youtube-video').src = youtubeLink; // Ladda YouTube-länken dynamiskt
}

// Visa Agency Plus-beskrivningen
function showAgencyPlus() {
    document.getElementById('intro').style.display = 'none'; // Döljer intro-scenen
    document.getElementById('agencyPlus').style.display = 'block'; // Visar Agency Plus-sidan
}

// Starta spelet
function startGame() {
    document.getElementById('agencyPlus').style.display = 'none'; // Döljer Agency Plus-sidan
    document.getElementById('content').style.display = 'block'; // Visar frågorna
    loadQuestions(); // Laddar frågorna
}

// Funktion för att ladda frågor från JSON
function loadQuestions() {
    fetch('data.json')
        .then(response => response.json())
        .then(data => {
            const contentDiv = document.getElementById('content');
            let currentIndex = 0;

            // Funktion för att visa nästa fråga och video
            function showNextQuestion() {
                if (currentIndex >= data.length) {
                    // Om alla frågor har visats, visa avslutningen
                    contentDiv.style.display = 'none';
                    document.getElementById('end').style.display = 'block';
                    return;
                }

                const questionData = data[currentIndex];

                // Bygg frågetext, bakgrundsbild och YouTube-video
                contentDiv.style.backgroundImage = `url(${questionData.background_image})`;
                contentDiv.innerHTML = `
                    <div class="dialogue-box">
                        <h2>Fråga ${currentIndex + 1}</h2>
                        <p>${questionData.question}</p>
                        <button onclick="showVideo('${questionData.youtube_link}')">Video</button>
                    </div>
                    <div id="video-container" style="display: none;">
                        <iframe width="560" height="315" id="youtube-video" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        <button onclick="nextQuestion()">Nästa Fråga</button>
                    </div>
                `;
            }

            // Funktion för att visa nästa fråga
            window.nextQuestion = function() {
                currentIndex++;
                showNextQuestion();  // Ladda nästa fråga
            }

            // Starta med att visa första frågan
            showNextQuestion();
        })
        .catch(error => console.error('Error fetching data:', error));
}

// Funktion för att dela insikt via e-post
function openEmail() {
    var insight = document.getElementById('insight').value;
    var email = document.getElementById('email').value;

    if (insight && email) {
        var subject = encodeURIComponent("Jag har en insikt att dela med dig!");
        var body = encodeURIComponent("Hej!\n\nJag har precis spelat Agency Plus och ville dela den här insikten med dig:\n\n" + insight + "\n\nVill du också reflektera? Testa spelet här: [Länk till spelet]");

        // Öppna användarens e-postklient med förifyllda fält
        window.location.href = "mailto:" + email + "?subject=" + subject + "&body=" + body;
    } else {
        alert("Fyll i både din insikt och en e-postadress.");
    }
}
