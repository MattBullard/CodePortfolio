function calcDateDiffFromToday(theDate) {
    var today = new Date(); // Current date

    var dateParts = theDate.split("/");
    var anotherDate = new Date(dateParts[2], (dateParts[0] - 1), dateParts[1]);

    var millisecondsPerDay = 1000 * 60 * 60 * 24;
    var millisBetween = anotherDate.getTime() - today.getTime();
    var days = millisBetween / millisecondsPerDay;

    return (Math.floor(days) + 1);

}
