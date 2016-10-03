using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using Microsoft.Xna.Framework;
using Microsoft.Xna.Framework.Graphics;

namespace GD327_Final_Project
{
    public class MineController : DrawableGameComponent
    {
        public enum MineType
        { Big, Small, Random, Empty }


        public static Texture2D BigMineTexture { get; private set; }
        public static Texture2D SmallMineTexture { get; private set; }

        List<Mine> _mines = new List<Mine>();
        public float MilisecondsToNextMine { get; set; }
        public float MinimumMilisecondsBetweenMines { get; set; }
        public float MaximumMilisecondsBetweenMines { get; set; }

        private SpriteBatch _spritebatch;
        GraphicsDeviceManager _graphics;
        static Random _rnd = new Random();
        //Texture2D _meteorTexture, _smallMeteorTexture;
        public bool CreateNewMines { get; set; }

        public MineController(Game game, GraphicsDeviceManager graphics, SpriteBatch spritebatch, float minMsBetweenMines = 10000, float maxMsBetweenMines = 20000)
            : base(game)
        {
            _spritebatch = spritebatch;
            _graphics = graphics;
            MilisecondsToNextMine = MinimumMilisecondsBetweenMines;
            MinimumMilisecondsBetweenMines = minMsBetweenMines;
            MaximumMilisecondsBetweenMines = maxMsBetweenMines;
            BigMineTexture = Game.Content.Load<Texture2D>("Space_Mines");
            SmallMineTexture = Game.Content.Load<Texture2D>("Small_Mine");
            CreateNewMines = true;
        }

        public override void Update(GameTime gameTime)
        {
            base.Update(gameTime);
            if (CreateNewMines)
            {

                if (MilisecondsToNextMine >= 0)
                {
                    MilisecondsToNextMine -= gameTime.ElapsedGameTime.Milliseconds;
                    if (MilisecondsToNextMine < 0)
                    {
                        AddMeteor();
                        MilisecondsToNextMine = (float)_rnd.NextDouble() * (MaximumMilisecondsBetweenMines - MinimumMilisecondsBetweenMines) + MinimumMilisecondsBetweenMines;
                    }
                }

            }
            for (int i = _mines.Count - 1; i >= 0; i--)
            {
                _mines[i].Update(gameTime);
                if (_mines[i].Position.Y > _graphics.PreferredBackBufferHeight + 200)
                {
                    _mines.RemoveAt(i);

                }
            }
        }

        private Mine AddMine(Vector2 position, MineType wantedType = MineType.Random)
        {

            bool isBigMine = false;
            if (wantedType == MineType.Big)
            {
                isBigMine = true;
            }
            else if (wantedType == MineType.Random)
            {
                isBigMine = _rnd.Next(4) == 0;
            }


            Vector2 movement = new Vector2((float)_rnd.NextDouble() * .3f - .15f, (float)_rnd.NextDouble() * .2f + 0.1f);
            MineType type = isBigMine ? MineType.Big : MineType.Small;

            Mine mine = new Mine(Game, position, _spritebatch, type, 1, 1, (float)_rnd.NextDouble(), (float)(0.005f * _rnd.NextDouble() + 0.003f) * (_rnd.Next(2) == 0 ? 1 : -1));
            if (isBigMine) movement /= 2;
            mine.Movement = movement;
            _mines.Add(mine);
            return mine;
        }

        private void AddMeteor(MineType type = MineType.Random)
        {
            Vector2 position = new Vector2((float)_rnd.NextDouble() * (_graphics.PreferredBackBufferWidth * .6f) + (_graphics.PreferredBackBufferWidth * .2f), -100);
            AddMine(position, type);
        }


        public override void Draw(GameTime gameTime)
        {
            foreach (var meteor in _mines)
            {
                meteor.Draw(gameTime);
            }
            base.Draw(gameTime);
        }

        public Mine CheckCollision(Vector2 pointToCheck, bool removeOnCollision = true, float radius = 0)
        {
            Mine hitMine = null;
            for (int i = _mines.Count - 1; i >= 0; i--)
            {
                if (Vector2.Distance(_mines[i].Position, pointToCheck) < _mines[i].BoundingSphereRadius + radius)
                {
                    hitMine = _mines[i];
                    if (removeOnCollision)
                    {
                        if (hitMine.Type == MineType.Big)
                        {
                            for (int meteorCounter = 0; meteorCounter < 3; meteorCounter++)
                            {
                                Mine mine = AddMine(pointToCheck, MineType.Small);
                                int roll = _rnd.Next(12);
                                if (roll < 2)
                                {
                                    mine.PowerUp = PowerUp.Shield;
                                    mine.Movement *= .7f;
                                }
                                else if (roll < 4)
                                {
                                    mine.PowerUp = PowerUp.GunUpgrade;
                                    mine.Movement *= .7f;
                                }
                            }
                        }
                        _mines.RemoveAt(i);
                    }
                    return hitMine;
                }
            }
            return hitMine;
        }
    }
}
