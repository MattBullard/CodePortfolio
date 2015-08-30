public int rollEncounterDice(List<int> dicePool, int rollBonus)
{
    // move through the list of dice, and get a random number.
    // when all dice are rolled, add the rollBonus, then 
    // return the result

    int totalOfDiceRolled = 0;
    int roll = 0;

    for (int i = 0; i < dicePool.Count(); i++)
    {
        if (dicePool[i] > 0)
        {
            roll = dh.rnd.Next(1, dicePool[i]);
            Console.WriteLine("1d" + dicePool[i] + " Rolled a " + roll);
        }
        totalOfDiceRolled += roll;
    }

    return (totalOfDiceRolled + rollBonus);
}
