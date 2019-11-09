(function () {
    var DogLoader = function (view, util) {
        this.view = view;
        this.util = util;

        this.load = function () {
            var xhr = new XMLHttpRequest();
            var view = this.view;
            var util = this.util;

            xhr.open("POST", "dogs-playing-poker.png", true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.responseType = "blob";

            view.status.className = "text-muted";
            view.status.innerHTML = "Your dogs are loading &hellip;";

            view.disableForm();

            xhr.onload = function () {
                view.enableForm();

                if (200 === this.status) {
                    var responseId = String(this.getResponseHeader("Dogs-Playing-Poker-Id"));
                    var cards = String(this.getResponseHeader("Dogs-Playing-Poker-Cards")).split(",");

                    view.caption.innerHTML = "The dogs have been dealt " + util.buildCardMessage(cards)
                        + ".<br />Dogs Playing Poker image #" + util.escapeHtml(responseId) + ".";
                    view.image.src = URL.createObjectURL(this.response);
                    view.status.innerText = "Your dogs have loaded successfully!";
                    view.status.className = "text-success";
                    return;
                }

                view.status.innerHTML = "There was an error!";
                view.status.className = "text-error";
            };

            xhr.onerror = function () {
                view.enableForm();

                view.status.innerText = "There was a network error";
                view.status.className = "text-error";
            };

            xhr.send("permutationId=" + view.permutationId.value);
        };
    };

    var View = function () {
        this.caption = document.getElementById("dogs-playing-poker-caption");
        this.formReset = document.getElementById("dogs-playing-poker-reset");
        this.formSubmit = document.getElementById("dogs-playing-poker-submit");
        this.image = document.getElementById("dogs-playing-poker");
        this.permutationId = document.getElementById("dogs-playing-poker-permutation-id");
        this.status = document.getElementById("dogs-playing-poker-status");

        this.permutationId.value = "";

        var permutationId = this.permutationId;
        this.formReset.addEventListener("click", function () {
            permutationId.value = "";
        });

        this.permutationId.addEventListener("keyup", function () {
            this.value = this.value.replace(/\D/g, "").replace(/^0+/, "");
        });

        this.enableForm = function () {
            this.formReset.disabled = false;
            this.formSubmit.disabled = false;
            this.permutationId.disabled = false;
        };

        this.disableForm = function () {
            this.formReset.disabled = true;
            this.formSubmit.disabled = true;
            this.permutationId.disabled = true;
        };
    };

    var Util = function () {
        this.escapeHtml = function (text) {
            var tempDiv = document.createElement("div");
            tempDiv.innerText = text;
            return tempDiv.innerHTML;
        };

        this.buildCardMessage = function (cards) {
            if (0 === cards.length) {
                return "nothing";
            }

            var formattedCards = [];
            for (var i = 0; i < cards.length; i++) {
                formattedCards.push(this.formatCard(cards[i]));
            }

            if (1 === formattedCards.length) {
                return formattedCards.pop();
            }

            if (2 === formattedCards.length) {
                return formattedCards[0] + " and " + formattedCards[1];
            }

            var lastCard = formattedCards.pop();
            var cardMessage = formattedCards.join(", ");
            return cardMessage + ", and " + lastCard;
        };

        this.formatCard = function (card) {
            card = parseInt(card, 10);
            if (isNaN(card) || card < 0 || card > 51) {
                return "??";
            }

            var rank;
            var rankHtml;
            var suit;
            var suitHtml;
            var cssClass;

            rank = (card + 1) % 13;
            if (0 === rank) {
                rank = 13;
            }
            suit = Math.floor(card / 13);

            switch (rank) {
                case 1:
                    rankHtml = "A";
                    break;
                case 11:
                    rankHtml = "J";
                    break;
                case 12:
                    rankHtml = "Q";
                    break;
                case 13:
                    rankHtml = "K";
                    break;
                default:
                    rankHtml = String(rank);
                    break;
            }

            switch (suit) {
                case 0:
                    cssClass = "text-dark";
                    suitHtml = "&clubsuit;";
                    break;
                case 1:
                    cssClass = "text-danger";
                    suitHtml = "&diamondsuit;";
                    break;
                case 2:
                    cssClass = "text-danger";
                    suitHtml = "&heartsuit;";
                    break;
                case 3:
                    cssClass = "text-dark";
                    suitHtml = "&spadesuit;";
                    break;
            }

            return '<span class="' + cssClass + '">' + rankHtml + suitHtml + '</span>';
        }
    };

    window.addEventListener("load", function () {
        var loader = new DogLoader(new View(), new Util());
        loader.load();

        loader.view.formSubmit.addEventListener("click", function () {
            loader.load();
        });
    });
})();
