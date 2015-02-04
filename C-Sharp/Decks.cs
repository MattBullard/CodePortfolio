using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Maingame
{

    public class Deck
    {
        // Represents a simple last-in-first-out (LIFO) non-generic collection of objects.
        private List<Card> cards = new List<Card>(); 

        public Deck()
        {

        }

        public int cardCount()
        {
            return cards.Count;
        }

        public void emptyDeck()
        {
            cards.Clear();
        }

        public void addCard(Card c)
        {
            cards.Add(c);
        }

        
        public void shuffleDeck()
        {
            Random rnd = new Random();  

            for (int i = 0; i < cards.Count; i++)
            {
                Card temp = cards[i];
                int randomIndex = rnd.Next(cards.Count);
                cards[i] = cards[randomIndex];
                cards[randomIndex] = temp;
            }
            // shuffle twice
            for (int i = 0; i < cards.Count; i++)
            {
                Card temp = cards[i];
                int randomIndex = rnd.Next(cards.Count);
                cards[i] = cards[randomIndex];
                cards[randomIndex] = temp;
            }
        
        }

    }
}
