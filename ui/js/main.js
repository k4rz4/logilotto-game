const clientInput = document.getElementById("client_id");
const stakeInput = document.getElementById("stake");
const betNumbersInput = document.getElementById("bet_numbers");
const submitButton = document.getElementById("placeBet");
const moneyLabel = document.getElementById("money");
const countdownLabel = document.getElementById('countdown');
const table = document.getElementById("betsTableBody");
var countdownTimer;

var initialTime = 0;
var seconds;

window.addEventListener('load', function() {
    clientInput.value = "";
    stakeInput.value = "";
    betNumbersInput.value = "";
    ajax('http://localhost/bets/list', "GET", null, function(data) {
        pageload(data);
        initialTime = data.data.nextDrawPull.time;
        seconds = initialTime;
        var countdownTimer = setInterval('timer()', 1000);
    })
})

clientInput.addEventListener('focusout', (event) => {
    let body = JSON.stringify({
        "client_id": clientInput.value
    })

    ajax("http://localhost/clients/validate", 'POST', body, function(data) {
        if (data.data.client_status) {
            moneyLabel.textContent = "$" + data.data.balance;
        } else {
            moneyLabel.textContent = "$0";
            alert("Not a valid client");
        }
    })
});

submitButton.addEventListener('click', (event) => {
    let betNumbersArray = betNumbersInput.value.replace(/,\s*$/, "").split(',')
    if (betNumbersArray.length == 7) {
        if (stakeInput.value < 1 || stakeInput.value !== "") {
            let body = JSON.stringify({
                client_id: clientInput.value,
                placed_numbers: betNumbersArray.join(","),
                stake: stakeInput.value
            })

            ajax("http://localhost/bets/place", 'POST', body, function(data) {
                if (data.status === 500) {
                    alert(data.Error);
                } else {
                    moneyLabel.textContent = "$" + data.data.balance;
                    delete data.data['balance'];
                    console.log(data)
                    CreateTableFromJSON([data.data])
                }
            })
        } else {
            alert("Stake can't be empty or 0");
        }
    } else {
        alert("Check bet numbers. Enter 7 numbers, after every number add comma.");
        return false
    }
})

function pageload(data) {
    while (table.hasChildNodes()) {
        table.removeChild(table.lastChild);
    }
    if (data.data.bets != undefined) {
        CreateTableFromJSON(data.data.bets)
    }
    var draw_numbers_arr = data.data.nextDrawPull.draw_numbers.split(",");
    var parent = document.getElementById("drawnNumbers");

    while (parent.hasChildNodes()) {
        parent.removeChild(parent.lastChild);
    }

    for (var i = 0; i <= draw_numbers_arr.length - 1; i++) {
        var newEl = document.createElement('span');
        newEl.className = "dot red";
        newEl.innerHTML = draw_numbers_arr[i];
        parent.appendChild(newEl);
    }
}

function ajax(url, type, body, callback) {
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4) {
            try {
                var data = JSON.parse(xmlhttp.responseText);
            } catch (err) {
                console.log(err.message + " in " + xmlhttp.responseText);
                return;
            }
            callback(data);
        }
    };
    xmlhttp.open(type, url, true);
    xmlhttp.send(body);
}

function CreateTableFromJSON(json) {
    let col = [];
    for (let i = 0; i < json.length; i++) {
        for (let key in json[i]) {
            if (col.indexOf(key) === -1) {
                col.push(key);
            }
        }
    }

    for (let i = 0; i < json.length; i++) {

        tr = table.insertRow(-1);

        for (let j = 0; j < col.length; j++) {
            let tabCell = tr.insertCell(-1);
            tabCell.innerHTML = (json[i][col[j]] === null) ? "pending" : json[i][col[j]];
        }
    }
}

function timer() {

    var days = Math.floor(seconds / 24 / 60 / 60);
    var hoursLeft = Math.floor((seconds) - (days * 86400));
    var hours = Math.floor(hoursLeft / 3600);
    var minutesLeft = Math.floor((hoursLeft) - (hours * 3600));
    var minutes = Math.floor(minutesLeft / 60);
    var remainingSeconds = seconds % 60;
    if (remainingSeconds < 10) {
        remainingSeconds = "0" + remainingSeconds;
    }
    countdownLabel.innerHTML = "0" + minutes + ":" + remainingSeconds + "<i>min</i>";
    if (seconds == 0) {
        ajax('http://localhost/bets/list', "GET", null, function(data) {
            seconds = data.data.nextDrawPull.time
            pageload(data);
            moneyLabel.textContent = "$0";
            clientInput.value = "";
        })
    } else {
        seconds--;
    }
}
