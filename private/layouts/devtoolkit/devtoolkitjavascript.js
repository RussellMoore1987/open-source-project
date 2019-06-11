// Javascript for the devtoolkit

// Closing the messages handling
var closeIcon = document.getElementById("close-icon");
var messages = document.getElementById("messages-section");

// Listen for the click on the close button
closeIcon.addEventListener("click", function() {
    messages.classList.add("hidden");
});

// Search table card handling
var searchBar = document.getElementById("search");

// Listen for a change on the searchBar then initiate the search
searchBar.addEventListener("keyup", search);


// Search function
function search() {
    // Define the searchString
    searchString = searchBar.value;

    // Get the list of table cards
    var cards_array = document.getElementsByClassName("card");

    // If the searchstring is defined then search the cards
    if(searchString !== "") {

        // Search the list of all the table cards to find matches
        for(var i = 0; i < cards_array.length; i++) {

            // Get the title of the card to check our search against
            var cardTitle = cards_array[i].children[0].innerHTML;

            // Lowercase the card title and search string to make the search insensitive
            var lowerCardTitle = cardTitle.toLowerCase();
            var lowerSearchString = searchString.toLowerCase();

            // Search the title, if the string does not match then hide the card
            if(lowerCardTitle.substring(0, lowerSearchString.length) != lowerSearchString) {
                cards_array[i].classList.add("hidden");
            }

        }

    // Else the search string is not defined or empty, show all the cards
    } else {

        // Search the list of all the table cards to find matches
        for(var i = 0; i < cards_array.length; i++)  {

            // Show each of the cards
            cards_array[i].classList.remove("hidden");

        }
    }
}