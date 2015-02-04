using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Maingame
{
    public class Hero
    {

        private string name;
        private heroArchetypes archetype;
        private int speed;
        private int health;
        private int stamina;
        private defenseDice defense;

        private int willpower;
        private int might;
        private int knowledge;
        private int awareness;

        private int fatigue;
        private int damage;

        private string abilityText;
        private string featText;

        private System.Drawing.Bitmap faceImage;

        public Hero()
        {

        }

        public Hero( Hero previousHero ) // copy constructor
        {
            Name = previousHero.Name;
            FaceImage = previousHero.faceImage;
            AbilityText = previousHero.AbilityText;
            FeatText = previousHero.FeatText;
            Archetype = previousHero.Archetype;
            Speed = previousHero.Speed;
            Health = previousHero.Health;
            Stamina = previousHero.Stamina;
            Defense = previousHero.Defense;
            Willpower = previousHero.Willpower;
            Might = previousHero.Might;
            Knowledge = previousHero.Knowledge;
            Awareness = previousHero.Awareness;
            Fatigue = 0;
            Damage = 0;

        }

        public string Name
        {
            get { return name; }
            set { name = value; }
        }

        public System.Drawing.Bitmap FaceImage
        {
            get { return faceImage; }
            set { faceImage = value; }
        }

        public string AbilityText
        {
            get { return abilityText; }
            set { abilityText = value; }
        }

        public string FeatText
        {
            get { return featText; }
            set { featText = value; }
        }

        public heroArchetypes Archetype
        {
            get { return archetype; }
            set { archetype = value; }
        }

        public int Speed
        {
            get { return speed; }
            set { speed = value; }
        }

        public int Fatigue
        {
            get { return fatigue; }
            set { fatigue = value; }
        }

        public int Damage
        {
            get { return damage; }
            set { damage = value; }
        }

        public int Health
        {
            get { return health; }
            set { health = value; }
        }

        public int Stamina
        {
            get { return stamina; }
            set { stamina = value; }
        }

        public defenseDice Defense
        {
            get { return defense; }
            set { defense = value; }
        }
        public int Willpower
        {
            get { return willpower; }
            set { willpower = value; }
        }

        public int Might
        {
            get { return might; }
            set { might = value; }
        }

        public int Knowledge
        {
            get { return knowledge; }
            set { knowledge = value; }
        }

        public int Awareness
        {
            get { return awareness; }
            set { awareness = value; }
        }
    }
}
