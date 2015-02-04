using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Maingame
{
    public class Card
    {

        private List<CardAbility> cardAbilities = new List<CardAbility>(); 

        private string cardName;
        private int cardStatus;
        private int cardOwner;


        public Card(string n)
        {
            cardName = n;
            cardStatus = frmMain.REFRESHED;
            cardOwner = frmMain.THEBOX;
        }

        public int CardStatus
        {
            get { return cardStatus; }
            set { cardStatus = value; }
        }

        public int CardOwner
        {
            get { return cardOwner; }
            set { cardOwner = value; }
        }


        public string CardName
        {
            get { return cardName; }
            set { cardName = value; }
        }

        public void addActivatedAbility(CardAbility c)
        {
            cardAbilities.Add(c);
        }



    }
}
