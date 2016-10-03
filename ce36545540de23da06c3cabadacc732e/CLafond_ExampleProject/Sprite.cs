using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using Microsoft.Xna.Framework;
using Microsoft.Xna.Framework.Graphics;

namespace GD327_Final_Project
{
    public class Sprite : DrawableGameComponent
    {

        public SpriteBatch SpriteBatch { get; protected set; }
        protected Vector2 _textureSize, _halfTextureSize;
        public Color Color { get; set; }
        private Texture2D _texture;

        public Texture2D Texture
        {
            get { return _texture; }
            set
            {
                _texture = value;
                _textureSize = new Vector2(_texture.Width, _texture.Height);
                _halfTextureSize = _textureSize / 2;
                float heightWidthAverage = Math.Abs(_texture.Width - _texture.Height) / 2 + Math.Min(_texture.Height, _texture.Width);
                BoundingSphereRadius = heightWidthAverage * .5f;

            }
        }

        public Vector2 Position { get; set; }
        public Vector2 Movement { get; set; }
        public float Opacity { get; set; }
        public float Scale { get; set; }
        public float Rotation { get; set; }
        public float RotationPerUpdate { get; set; }

        public float BoundingSphereRadius { get; set; }

        public Sprite(Game game, Texture2D texture, Vector2 position, SpriteBatch batch, float scale = 1, float opacity = 1, float rotation = 0, float rotationPerUpdate = 0)
            : base(game)
        {
            Texture = texture;
            Position = position;
            SpriteBatch = batch;
            Scale = scale;
            Opacity = opacity;
            Rotation = rotation;
            RotationPerUpdate = rotationPerUpdate;
            Color = Color.White;
        }

        public override void Draw(GameTime gameTime)
        {
            base.Draw(gameTime);
            //Rectangle destRect = new Rectangle((int)(Position.X + .5f - _halfTextureSize), (int)(Position.X + .5f - _halfTextureSize), (int)Texture.Width, (int)Texture.Height);
            SpriteBatch.Draw(_texture, Position, null, Color * Opacity, Rotation, _halfTextureSize, Scale, SpriteEffects.None, 0);
        }

        public override void Update(GameTime gameTime)
        {
            Position += Movement * (float)gameTime.ElapsedGameTime.TotalMilliseconds;
            Rotation += RotationPerUpdate * (float)gameTime.ElapsedGameTime.TotalMilliseconds;
        }
    }
}
