using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Maingame
{
    public class CardAbility
    {
        private string abilityName;
        private int effectId;
        private string abilityText;

        public CardAbility(string n)
        {
            abilityName = n;
        }

        public CardAbility()
        {
            abilityName = "";
        }

        public string AbilityName
        {
            get { return abilityName; }
            set { abilityName = value; }
        }

        public int EffectId
        {
            get { return effectId; }
            set { effectId = value; }
        }

        public string AbilityText
        {
            get { return abilityText; }
            set { abilityText = value; }
        }


    }
}
