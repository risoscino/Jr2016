//Author: Christopher J. Lafond
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using Microsoft.Xna.Framework;
using Microsoft.Xna.Framework.Graphics;

namespace GD327_Final_Project
{
    class PlayerSpaceShip : Sprite
    {

        public Texture2D TextureLeft { get; protected set; }
        public Texture2D TextureRight { get; protected set; }
        public Texture2D TextureShield { get; protected set; }
        public int ShotsFired { get; set; }
        public int ShotsHit { get; set; }
        public bool IsInvincible { get { return InvincibleTimeLeft > 0; } }
        public bool IsVisible { get { return InvincibleTimeLeft < 1000; } }
        public float InvincibleTimeLeft { get; set; }

        public int Points { get; set; }
        public bool HasShield { get; set; }
        public float Health { get; set; }
        public float MaxHealth { get; set; }
        Vector2 _halfShieldSize;
        readonly Random _rnd = new Random();

        private int _guns;

        public int Guns
        {
            get { return _guns; }
            set
            {
                _guns = (int)MathHelper.Clamp(value, 1, 3);
            }
        }


        public PlayerSpaceShip(Game game, Texture2D textureLeft, Texture2D textureStandard, Texture2D textureRight, Texture2D textureShield, Vector2 position, SpriteBatch batch)
            : base(game, textureStandard, position, batch)
        {
            MaxHealth = 100;
            TextureLeft = textureLeft;
            TextureRight = textureRight;
            TextureShield = textureShield;
            _halfShieldSize = new Vector2(TextureShield.Width, TextureShield.Height) / 2;
            Reset();
        }


        public override void Draw(GameTime gameTime)
        {
            float opacity = 1;
            Texture2D texture = null;
            if (InvincibleTimeLeft > 1000)
            {
                opacity = 0.2f;
            }
            else if (InvincibleTimeLeft > 0)
            {
                opacity = ((int)(gameTime.TotalGameTime.Milliseconds / 100)) % 2 == 0 ? .5f : 1f;
            }


            if (Movement.X < 0) { texture = TextureLeft; }
            else if (Movement.X > 0) { texture = TextureRight; }
            else { texture = Texture; }

            SpriteBatch.Draw(texture, Position, null, Color.White * opacity, 0, _halfTextureSize, 1, SpriteEffects.None, 0);

            if (HasShield)
            {
                float opacityValue = (float)(.6 + .4 * _rnd.NextDouble());
                SpriteBatch.Draw(TextureShield, Position - Vector2.UnitY * 15, null, Color.White * opacityValue, 0, _halfShieldSize, 1, SpriteEffects.None, 0);
                SpriteBatch.Draw(TextureShield, Position - Vector2.UnitY * 20, null, Color.White * opacityValue, 0, _halfShieldSize, 1, SpriteEffects.None, 0);
            }
        }

        public override void Update(GameTime gameTime)
        {
            InvincibleTimeLeft -= (float)gameTime.ElapsedGameTime.TotalMilliseconds;
            base.Update(gameTime);
        }

        public void Reset()
        {
            Health = MaxHealth;
            HasShield = false;
            Guns = 1;
            ShotsFired = 0;
            ShotsHit = 0;
        }
    }
}
