using System;
using System.Collections.Generic;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Descent
{
   
    public class Map
    {
        private string mapName;
        private double mapScale;
        private int mapOffsetX;
        private int mapOffsetY;
        
        private monsterTraits trait1;
        private monsterTraits trait2;
        private monsterTraits trait3;
        private monsterTraits trait4;
        private monsterTraits trait5;

        public List<MapTile> tiles = new List<MapTile>();
        public List<SearchToken> searchTokens = new List<SearchToken>();
        public List<Point> startingPoints = new List<Point>();
        public List<SpaceStatus> spaces = new List<SpaceStatus>();
        public List<Monster> monsters = new List<Monster>();

        public Map(string n)
        {
            mapName = n;
            mapScale = 1.0;

            trait1 = monsterTraits.none;
            trait2 = monsterTraits.none;
            trait3 = monsterTraits.none;
            trait4 = monsterTraits.none;
            trait5 = monsterTraits.none;

        }

        public bool isFigureInSpace(int spotX, int spotY)
        {
            for (int i = 0; i < spaces.Count(); i++) {
                if (spaces[i].X == spotX && spaces[i].Y == spotY)
                {
                    // this says that there is an entry in the List.
                    if (spaces[i].Figure != mapFigures.none)
                    {
                        return true;
                    }
                }
            }
            return false;
        }

        public void addHeroToSpace(int spotX, int spotY)
        {
            bool spotFound = false;
            for (int i = 0; i < spaces.Count(); i++)
            {
                if (spaces[i].X == spotX && spaces[i].Y == spotY)
                {
                    spotFound = true;
                    spaces[i].Figure = mapFigures.hero;
                }
            }

            if (spotFound == false)
            {
                SpaceStatus tmpSpace = new SpaceStatus();
                tmpSpace.X = spotX;
                tmpSpace.Y = spotY;
                tmpSpace.Figure = mapFigures.hero;
                addSpaceStatus(tmpSpace);
            }
        }
        public void addMonsterToSpace(int spotX, int spotY)
        {
            bool spotFound = false;
            for (int i = 0; i < spaces.Count(); i++)
            {
                if (spaces[i].X == spotX && spaces[i].Y == spotY)
                {
                    spotFound = true;
                    spaces[i].Figure = mapFigures.monster;
                }
            }

            if (spotFound == false)
            {
                SpaceStatus tmpSpace = new SpaceStatus();
                tmpSpace.X = spotX;
                tmpSpace.Y = spotY;
                tmpSpace.Figure = mapFigures.monster;
                addSpaceStatus(tmpSpace);
            }
        }
        public void removeFigureFromSpace(int spotX, int spotY)
        {
            for (int i = 0; i < spaces.Count(); i++)
            {
                if (spaces[i].X == spotX && spaces[i].Y == spotY)
                {
                    spaces[i].Figure = mapFigures.none;
                }
            }
            // no action if the sopt is not found in the List.
        }

        public void addMonster(Monster m)
        {
            monsters.Add(m);
        }

        public monsterTraits Trait1
        {
            get { return trait1; }
            set { trait1 = value; }
        }

        public monsterTraits Trait2
        {
            get { return trait2; }
            set { trait2 = value; }
        }

        public monsterTraits Trait3
        {
            get { return trait3; }
            set { trait3 = value; }
        }

        public monsterTraits Trait4
        {
            get { return trait4; }
            set { trait4 = value; }
        }

        public monsterTraits Trait5
        {
            get { return trait5; }
            set { trait5 = value; }
        }

        public double MapScale
        {
            get { return mapScale; }
            set { mapScale = value; }
        }

        public int MapOffsetX
        {
            get { return mapOffsetX; }
            set { mapOffsetX = value; }
        }

        public int MapOffsetY
        {
            get { return mapOffsetY; }
            set { mapOffsetY = value; }
        }

        public string MapName
        {
            get { return mapName; }
            set { mapName = value; }
        }

        public void addTile(MapTile t)
        {
            tiles.Add(t);
        }

        public void addSpaceStatus(SpaceStatus ss)
        {
            spaces.Add(ss);
        }

        public void addSearchToken(SearchToken t)
        {
            searchTokens.Add(t);
        }

    }
}
