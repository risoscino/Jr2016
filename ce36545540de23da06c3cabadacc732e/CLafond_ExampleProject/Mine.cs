using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using Microsoft.Xna.Framework;
using Microsoft.Xna.Framework.Graphics;

namespace GD327_Final_Project
{
    public class Mine : Sprite
    {


        private PowerUp _powerup;

        public PowerUp PowerUp
        {
            get { return _powerup; }
            set
            {
                _powerup = value;
                switch (this.PowerUp)
                {
                    case PowerUp.Shield:
                        this.Color = Color.Red;
                        break;
                    case PowerUp.GunUpgrade:
                        this.Color = Color.Cyan;
                        break;
                    case PowerUp.Empty:
                        this.Color = Color.White;
                        break;
                }
            }
        }


        public MineController.MineType Type { get; private set; }
        public Mine(Game game, Vector2 position, SpriteBatch batch, MineController.MineType type, float scale = 1, float opacity = 1, float rotation = 0, float rotationPerUpdate = 0)
            : base(game, type == MineController.MineType.Big ? MineController.BigMineTexture : MineController.SmallMineTexture, position, batch, scale, opacity, rotation, rotationPerUpdate)
        {
            Type = type;
        }

        public override void Draw(GameTime gameTime)
        {
            base.Draw(gameTime);
            switch (PowerUp)
            {
                case PowerUp.Shield:
                    SpriteBatch.DrawString(TextController.Font, "Shield", Position - Vector2.One * 20, Color.White);
                    break;
                case PowerUp.GunUpgrade:
                    SpriteBatch.DrawString(TextController.Font, "Guns", Position - Vector2.One * 20, Color.White);
                    break;
            }

        }

    }
}
