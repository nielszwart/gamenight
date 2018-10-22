window.onload = function() {
	setInterval(function(){
		getScores();
	}, 10 * 1000);
};

function getScores()
{
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
        	var results = JSON.parse(this.responseText);
           	var totals = results.totals;
           	delete results['totals'];
           	
           	applyScores(totals, results);
        }
    };

    xhttp.open("GET", getScoresLink, true);
    xhttp.send();
}

function applyScores(totals, scores)
{
	if (totals) {
		var totalScoreDiv = document.getElementById('totalscore');

		for (var i = 0; i < Object.keys(totals).length; i++) {
			var score = totals[i];

			var scoreRowDiv = document.getElementById('totals-' + score.nickname);
			var newRow = false;
			if (!scoreRowDiv) {
				var scoreRowDiv = newScoreRow(true, score, '');
				newRow = true;
			}

			changePositionAndScore(scoreRowDiv, score, i);

			if (!newRow) {
				totalScoreDiv.removeChild(scoreRowDiv)
			}

			if (i === 0) {
				totalScoreDiv.prepend(scoreRowDiv);
			} else {
				totalScoreDiv.appendChild(scoreRowDiv);
			}			
		}
	}

	if (scores) {
		for (var j = 0; j < Object.keys(scores).length; j++) {
       		var gameScores = scores[j];
       		var slug = gameScores.slug;
       		delete gameScores['slug'];

			var gameScoreDiv = document.getElementById(slug + '-scores');

			for (var i = 0; i < Object.keys(gameScores).length; i++) {
				var score = gameScores[i];

				var scoreRowDiv = document.getElementById(slug + '-score-' + score.nickname);
				var newRow = false;
				if (!scoreRowDiv) {
					var scoreRowDiv = newScoreRow(false, score, slug);
					newRow = true;
				}

				changePositionAndScore(scoreRowDiv, score, i);
				changePointsAndGames(scoreRowDiv, score);
		
				if (!newRow) {
					gameScoreDiv.removeChild(scoreRowDiv)
				}
		
				if (i === 0) {
					gameScoreDiv.prepend(scoreRowDiv);
				} else {
					gameScoreDiv.appendChild(scoreRowDiv);
				}	
			}
		}
	}
}

function changePositionAndScore(scoreRowDiv, score, i)
{
	var positionDiv = scoreRowDiv.getElementsByClassName('position')[0];
	positionDiv.innerHTML = i+1;

	var scoreDiv = scoreRowDiv.getElementsByClassName('score')[0];
	scoreDiv.innerHTML = score.score;
}

function changePointsAndGames(scoreRowDiv, score)
{
	var gamesDiv = scoreRowDiv.getElementsByClassName('games')[0];
	gamesDiv.innerHTML = score.games;

	var pointsDiv = scoreRowDiv.getElementsByClassName('points')[0];
	pointsDiv.innerHTML = score.points;
}

function newScoreRow(totalsRow, score, game)
{
	var dummyScoreRow = document.getElementById("new-score-row");
	var newScoreRow = dummyScoreRow.cloneNode(true);

	var nicknameDiv = newScoreRow.getElementsByClassName('nickname')[0];
	nicknameDiv.innerHTML = score.nickname;

	if (totalsRow) {
		newScoreRow.setAttribute("id", "totals-" + score.nickname);
		newScoreRow.removeChild(newScoreRow.getElementsByClassName('points')[0]);
		newScoreRow.removeChild(newScoreRow.getElementsByClassName('games')[0]);
	} else {
		newScoreRow.setAttribute("id", game + "-score-" + score.nickname);
	}

	return newScoreRow;
}