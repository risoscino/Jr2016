using System;
using System.Collections.Generic;
using System.Linq;
using Microsoft.Xna.Framework;
using Microsoft.Xna.Framework.Audio;
using Microsoft.Xna.Framework.Content;
using Microsoft.Xna.Framework.GamerServices;
using Microsoft.Xna.Framework.Graphics;
using Microsoft.Xna.Framework.Input;
using Microsoft.Xna.Framework.Media;

namespace GD327_Final_Project
{

    #region Enums

    public enum PowerUp { Empty, Shield, GunUpgrade }
    public enum GameState { Title, Playing, GameOver }

    #endregion

    public class Game1 : Microsoft.Xna.Framework.Game
    {

        //TODO:
        //forskellige skurke/farver
        //end of level boss - UFO - mange skud

        #region Variables

        int _wave;
        int _livesLeft = 3;
        Vector2 _movement;
        GameState _gameState;

        GraphicsDeviceManager graphics;
        SpriteBatch spriteBatch;


        List<Vector2> _shots = new List<Vector2>();
        List<Vector2> _enemyShots = new List<Vector2>();
        List<Sprite> _enemies = new List<Sprite>();
        List<Explosion> _explosions = new List<Explosion>();
        //List<HighScore> _highScores;

        Texture2D _greenShot, _redShot, _life, _enemyShip, _enemyUfo, _greenExplosion, _redExplosion, _background;
        PlayerSpaceShip _spaceship;
        SoundEffect _laserSound, _explosionSound, _enemyLaserSound;
        SpriteFont _defaultFont, _smallFont;
        // Ufo _ufo;
        //DateTime _nextUfoShowing;

        MineController _mineController;
        TextController _textController;

        Random _rnd = new Random();
        private int _nextFreeLife = 10000;

        //get half height - the aliens don't go below here, so we don't need
        //to check collision between playershots and aliens below that line
        float _halfScreenHeight;

        //the spaceship doesn't moveup/down so we can be sure that 
        //we don't need to check collisions with anything below this line
        float _borderOverSpaceship;

        KeyboardState _currentKeyboard, _oldKeyboard;
        //GameScreen activeScreen;
        Rectangle _bounds = new Rectangle(0, -512, 1024, 2048);
        double _chanceOfEnemyFirePerUpdate = .0005;
        bool _allKeysReleased;

        float _backgroundYoffset;

        #endregion


        #region Properties

        public Vector2 WindowSize               //easy way of getting the windowsize
        {
            get { return new Vector2(GraphicsDevice.Viewport.Width, GraphicsDevice.Viewport.Height); }
        }

        public Vector2 WindowCenter
        {
            get
            {
                return WindowSize / 2;
            }
        }


        GameState GameState
        {
            set
            {
                _gameState = value;

                switch (_gameState)
                {
                    case GameState.Title:
                        Components.Clear();
                        break;
                    case GameState.Playing:
                        NewGame();
                        break;
                    case GameState.GameOver:
                        //_meteorController.CreateNewMeteors = false;
                        // _//highScores.Add(new HighScore { Text = "Jakob", Score = _spaceship.Points * 100 });
                        //HighScoreController.SaveHighScores(_highScores);
                        break;

                    default:
                        break;
                }
            }
            get { return _gameState; }
        }

        #endregion


        #region Constructor and LoadContent

        public Game1()
        {
            graphics = new GraphicsDeviceManager(this);

            float ratio = GraphicsAdapter.DefaultAdapter.CurrentDisplayMode.AspectRatio;
            foreach (DisplayMode displayMode in GraphicsAdapter.DefaultAdapter.SupportedDisplayModes)
            {
                if (displayMode.Height >= 720 && ratio == displayMode.AspectRatio)
                {
                    graphics.PreferredBackBufferWidth = displayMode.Width;
                    graphics.PreferredBackBufferHeight = (int)(displayMode.Width / displayMode.AspectRatio);
                    break;
                }
            }

            //graphics.IsFullScreen = true;

            _halfScreenHeight = graphics.PreferredBackBufferHeight / 2;

            Content.RootDirectory = "Content";
          

            GameState = GD327_Final_Project.GameState.Title;
            IsFixedTimeStep = false;

        }


        protected override void LoadContent()
        {
            // Create a new SpriteBatch, which can be used to draw textures.
            spriteBatch = new SpriteBatch(GraphicsDevice);

           _laserSound = Content.Load<SoundEffect>("playershoot");
            _explosionSound = Content.Load<SoundEffect>("Sounds/explosion");
            _enemyLaserSound = Content.Load<SoundEffect>("Sounds/enemylaser");
            _greenShot = Content.Load<Texture2D>("laserGreen");
            _greenExplosion = Content.Load<Texture2D>("explosion");
            _redExplosion = Content.Load<Texture2D>("explosion");
            _redShot = Content.Load<Texture2D>("laserRed");
            _life = Content.Load<Texture2D>("player_2");
            _enemyShip = Content.Load<Texture2D>("Scorpion ship");
            _enemyUfo = Content.Load<Texture2D>("enemy sprite");
            _defaultFont = Content.Load<SpriteFont>("DefaultFont");
            _smallFont = Content.Load<SpriteFont>("smallFont");

            _background = CreateBackground();



            _mineController = new MineController(this, graphics, spriteBatch, 500, 1000);
            Components.Add(_mineController);
        }

        #endregion


        private void AddEnemy(int index)
        {
            Vector2 position = new Vector2(0, _rnd.Next(250) + 30);
            Vector2 movement = Vector2.Zero;
            float enemySpacing = 300 - _wave * 10;
            enemySpacing = MathHelper.Clamp(enemySpacing, 70, 1000);
            //first ten waves come from only one side (alternating between waves)
            //second ten waves come from both sides (alternating between each spaceship)
            if ((_wave < 10 && _wave % 2 == 0) || (_wave >= 10 && index % 2 == 0))
            {
                position.X = -300 - enemySpacing * (index + 1);
                movement = Vector2.UnitX * 0.2f;
            }
            else
            {
                position.X += graphics.PreferredBackBufferWidth + 300 + enemySpacing * (index + 1);
                movement = Vector2.UnitX * -0.2f; ;
            }

            if (_wave >= 20)
            {
                movement.Y = (float)(_rnd.NextDouble() * .4f - .2f);
            }
            Sprite newEnemy = new Sprite(this, _enemyShip, position, spriteBatch);
            newEnemy.Movement = movement;
            _enemies.Add(newEnemy);

        }

        protected override void Update(GameTime gameTime)
        {
            _oldKeyboard = _currentKeyboard;
            _currentKeyboard = Keyboard.GetState();
            if (GamePad.GetState(PlayerIndex.One).Buttons.Back == ButtonState.Pressed)
                this.Exit();


            //this is a bit of a hack :)
            //Because pressing the ENTER key to start the hostgame in Windows may be perceived as the 
            //"start the currently selected game" ENTER press, we ensure that the ENTER key has been up by
            //by setting a variable to true the first time an Update with no keys pressed has been detected
            if (!_allKeysReleased) { _allKeysReleased = _currentKeyboard.GetPressedKeys().Length == 0; }

            _backgroundYoffset += (float)(gameTime.ElapsedGameTime.Milliseconds / 3f);

            if (_backgroundYoffset >= -256) { _backgroundYoffset -= 256; }
            if (_livesLeft < 0) { GameState = GameState.GameOver; }

            for (int i = _shots.Count - 1; i >= 0; i--) { _shots[i] -= Vector2.UnitY * gameTime.ElapsedGameTime.Milliseconds; }

            for (int i = _enemyShots.Count - 1; i >= 0; i--) { _enemyShots[i] += Vector2.UnitY * .5f * gameTime.ElapsedGameTime.Milliseconds; }

            if (_wave > 20)
            {
                //make enemy shots slightly targeting when level is 20 or above
                for (int i = _enemyShots.Count - 1; i >= 0; i--)
                {
                    _enemyShots[i] += Vector2.UnitX * .1f * gameTime.ElapsedGameTime.Milliseconds * Math.Sign(_spaceship.Position.X - _enemyShots[i].X);
                }
            }

            switch (GameState)
            {
                case GameState.Title:
                    CheckInput();
                    break;
                case GameState.Playing:
                    _spaceship.Movement = Vector2.Zero;
                    CheckInput();
                    _spaceship.Movement += _movement;
                    CheckCollisions();
                    Cleanup();
                    UpdateEnemies(gameTime);
                    if (_spaceship.Points > _nextFreeLife)
                    {
                        _nextFreeLife += 10000;
                        AddExtraLife();
                    }
                    break;
                case GameState.GameOver:
                    _spaceship.Movement = Vector2.Zero;
                    CheckInput();
                    break;
            }

            base.Update(gameTime);

        }

        private void AddExtraLife()
        {
            _textController.AddTextSprite(WindowCenter, "Extra life!! :)", Color.ForestGreen, 1, 2, 1200);
            _livesLeft++;
        }

        private void UpdateEnemies(GameTime gameTime)
        {
            if (_enemies.Count == 0)
            {
                AddWave(gameTime);
            }

            foreach (var enemy in _enemies)
            {
                enemy.Update(gameTime);
                if ((enemy.Movement.X < 0 && enemy.Position.X <= 100) || (enemy.Movement.X > 0 && enemy.Position.X >= graphics.PreferredBackBufferWidth - 100))
                {
                    enemy.Movement = new Vector2(enemy.Movement.X * -1, enemy.Movement.Y);
                }

                //random fire from enemies on screen
                if (_rnd.NextDouble() < _chanceOfEnemyFirePerUpdate && enemy.Position.X > -20 && enemy.Position.X < graphics.PreferredBackBufferWidth + 20)
                {
                    EnemyFire(enemy);
                }

                if ((enemy.Movement.Y < 0 && enemy.Position.Y <= 50) || (enemy.Movement.Y > 0 && enemy.Position.Y >= graphics.PreferredBackBufferHeight / 2))
                {
                    enemy.Movement = new Vector2(enemy.Movement.X, enemy.Movement.Y * -1);
                }
            }

        }

        private void AddWave(GameTime gameTime)
        {
            _wave++;
            for (int i = 0; i < _wave; i++)
            {
                AddEnemy(i);
            }
            _chanceOfEnemyFirePerUpdate += .001;
            _chanceOfEnemyFirePerUpdate = MathHelper.Clamp((float)_chanceOfEnemyFirePerUpdate, 0, .010f);
            _mineController.MinimumMilisecondsBetweenMines -= 100;
            _mineController.MinimumMilisecondsBetweenMines = MathHelper.Clamp(_mineController.MinimumMilisecondsBetweenMines, 1000, 10000);
            _mineController.MaximumMilisecondsBetweenMines -= 200;
            _mineController.MaximumMilisecondsBetweenMines = MathHelper.Clamp(_mineController.MaximumMilisecondsBetweenMines, 1500, 10000);
            _textController.AddTextSprite(WindowCenter + Vector2.One * 40, "Wave " + _wave, Color.Pink, 2, 2.5f, 1200);

            _enemies.Sort((e1, e2) => e1.Position.Y.CompareTo(e2.Position.Y));
        }

        private void CheckInput()
        {
            if (GameState == GD327_Final_Project.GameState.Playing && _spaceship.IsVisible)
            {
                _movement = Vector2.Zero;

                if (_currentKeyboard.IsKeyDown(Keys.Left) && _spaceship.Position.X > 50) { _movement -= Vector2.UnitX * .5f; }
                if (_currentKeyboard.IsKeyDown(Keys.Right) && _spaceship.Position.X < graphics.PreferredBackBufferWidth - 50) { _movement += Vector2.UnitX * .5f; }
                if (_currentKeyboard.IsKeyDown(Keys.Up) && _spaceship.Position.Y > _halfScreenHeight + 100) { _movement -= Vector2.UnitY * .5f; }
                if (_currentKeyboard.IsKeyDown(Keys.Down) && _spaceship.Position.Y < GraphicsDevice.Viewport.Height - _spaceship.Texture.Height / 2) { _movement += Vector2.UnitY * .5f; }

                if (WasJustPressed(Keys.Space)) { Fire(); }

                if (WasJustPressed(Keys.Escape)) { GameState = GD327_Final_Project.GameState.Title; }

            }
            else
            {
                if (WasJustPressed(Keys.Escape)) { Exit(); }
            }
            if (WasJustPressed(Keys.F11))
            {
                graphics.ToggleFullScreen();
                graphics.ApplyChanges();
            }
            if (WasJustPressed(Keys.F10))
            {
                graphics.ToggleFullScreen();
                graphics.ApplyChanges();
            }
            if (WasJustPressed(Keys.Enter) && _allKeysReleased)
            {
                if (GameState == GameState.Playing)
                {
                    if (_currentKeyboard.IsKeyDown(Keys.RightShift)) { _enemies.Clear(); }
                }
                else { this.GameState = GD327_Final_Project.GameState.Playing; }
            }
        }

        private void CheckCollisions()
        {
            if (!_spaceship.IsInvincible)
            {
                CheckIfEnemyHitsSpaceship();
                CheckIfMinesHitsSpaceship();
            }

            CheckIfPlayerHitSomething();

        }

        private void CheckIfPlayerHitSomething()
        {
            for (int i = _shots.Count - 1; i >= 0; i--)
            {
                for (int enemyCounter = _enemies.Count - 1; enemyCounter >= 0; enemyCounter--)
                {
                    //don't check for collision below half screen height
                    if (_shots[i].Y > _halfScreenHeight)
                        continue;
                    Sprite enemy = _enemies[enemyCounter];
                    if (Vector2.DistanceSquared(_shots[i], enemy.Position) < 1400)
                    {
                        AddExplosion(_shots[i], _greenExplosion);
                        _textController.AddTextSprite(_shots[i], "100 points");
                        _spaceship.Points += 100;
                        _spaceship.ShotsHit++;
                        _shots.RemoveAt(i);
                        _enemies.RemoveAt(enemyCounter);
                        return;
                    }
                }

                //did we hit a meteor?
                Mine hitMine = _mineController.CheckCollision(_shots[i]);
                if (hitMine != null)
                {
                    AddExplosion(_shots[i], _greenExplosion);

                    int points = 0;
                    if (hitMine.Type == MineController.MineType.Big) { points = 10; }
                    else { points = 25; }
                    _spaceship.Points += points;
                    _spaceship.ShotsHit++;
                    _textController.AddTextSprite(_shots[i], points.ToString() + " points");
                    _shots.RemoveAt(i);
                    return;
                }
            }
        }

        private void CheckIfMinesHitsSpaceship()
        {
            //did a meteor hit the spaceship
            Mine hitMineSpaceship = _mineController.CheckCollision(_spaceship.Position, true, _spaceship.BoundingSphereRadius);
            if (hitMineSpaceship != null)
            {
                if (hitMineSpaceship.PowerUp == PowerUp.GunUpgrade)
                {
                    if (_spaceship.Guns < 3)
                    {
                        _spaceship.Guns++;
                        _textController.AddTextSprite(hitMineSpaceship.Position, "Guns upgraded!! :)", Color.Cyan);
                    }
                    else
                    {
                        _textController.AddTextSprite(hitMineSpaceship.Position, "500 points", Color.Red);
                        _spaceship.Points += 500;
                    }

                }
                else
                {
                    if (hitMineSpaceship.PowerUp == PowerUp.Shield)
                    {
                        if (_spaceship.HasShield)
                        {
                            _textController.AddTextSprite(hitMineSpaceship.Position, "500 points", Color.Red);
                            _spaceship.Points += 500;
                        }
                        else
                        {
                            _spaceship.HasShield = true;
                            _textController.AddTextSprite(hitMineSpaceship.Position, "SHIELD!! :)", Color.Red);
                        }
                    }
                    else
                    {
                        if (_spaceship.HasShield) { BlastShield(hitMineSpaceship.Position); }
                        else { Die(hitMineSpaceship.Position); }
                    }
                }
            }
        }

        private void CheckIfEnemyHitsSpaceship()
        {
            for (int i = _enemyShots.Count - 1; i >= 0; i--)
            {
                if (_enemyShots[i].Y < _borderOverSpaceship)
                    continue;
                if (_spaceship.HasShield)
                {
                    if (Vector2.DistanceSquared(_enemyShots[i], _spaceship.Position) < 5000)
                    {
                        BlastShield(_enemyShots[i]);
                        _enemyShots.RemoveAt(i);

                        break;
                    }
                }
                else if (Vector2.DistanceSquared(_enemyShots[i], _spaceship.Position) < 1000)
                {

                    Die(_enemyShots[i]);
                    _enemyShots.RemoveAt(i);
                    break;
                }
            }
        }

        void BlastShield(Vector2 collisionPosition)
        {
            AddExplosion(collisionPosition, _redExplosion);
            _textController.AddTextSprite(collisionPosition, "shield gone! :(");
            _spaceship.HasShield = false;
            _explosionSound.Play();
        }


        private void Die(Vector2 positionOfImpact)
        {
            AddExplosion(positionOfImpact, _redExplosion);
            for (int i = 0; i < 5; i++)
            {
                Vector2 explosionPoint = new Vector2(_rnd.Next(100) - 50, _rnd.Next(100) - 50);
                AddExplosion(positionOfImpact + explosionPoint, _redExplosion);
            }
            _textController.AddTextSprite(positionOfImpact, "Ouch!");
            NextLife();
            _explosionSound.Play();

        }

        private void Cleanup()
        {
            _shots.RemoveAll(shot => shot.Y < -10);
            for (int i = Components.Count - 1; i >= 0; i--)
            {
                if (Components[i] is Explosion)
                {
                    if (((Explosion)Components[i]).Opacity <= 0)
                        Components.RemoveAt(i);
                }
            }
        }

        protected override void Draw(GameTime gameTime)
        {
            GraphicsDevice.Clear(Color.Black);
            //spriteBatch.Begin(SpriteSortMode.Immediate, BlendState.NonPremultiplied, SamplerState.LinearClamp, DepthStencilState.Default, RasterizerState.CullNone);
            spriteBatch.Begin(SpriteSortMode.Immediate, BlendState.Opaque);

            //spriteBatch.Draw(Content.Load<Texture2D>("starBackground"), _bounds, new Rectangle(0, 0, 1024, 2048), Color.White);
            spriteBatch.Draw(_background, Vector2.UnitY * _backgroundYoffset, Color.White);
            spriteBatch.End();

            spriteBatch.Begin(SpriteSortMode.Texture, BlendState.AlphaBlend);
            base.Draw(gameTime);

            switch (GameState)
            {
                case GameState.Title:
                    spriteBatch.DrawString(_defaultFont, " Starwing Commander Saga", new Vector2(WindowCenter.X - _defaultFont.MeasureString("Starwing Commander Saga").X / 2, _halfScreenHeight - 60), Color.White);
                    spriteBatch.DrawString(_smallFont, "   CODE- Christopher J. Lafond", new Vector2(WindowCenter.X - _smallFont.MeasureString("CODE - Christopher J. Lafond").X / 2, _halfScreenHeight + 20), Color.LightCyan);
                    spriteBatch.DrawString(_smallFont, "    Visuals- Seth Pry", new Vector2(WindowCenter.X - _smallFont.MeasureString("Visuals - Seth Pry").X / 2, _halfScreenHeight + 60), Color.LightCyan);
                    spriteBatch.DrawString(_smallFont, "[ENTER] to begin", new Vector2(WindowSize.X - 300, graphics.PreferredBackBufferHeight - 100), Color.Silver);
                    spriteBatch.DrawString(_smallFont, "[F11] window/fullscreen", new Vector2(WindowSize.X - 300, graphics.PreferredBackBufferHeight - 70), Color.Silver);
                    spriteBatch.DrawString(_smallFont, "[F10] Instructions", new Vector2(WindowSize.X - 300, graphics.PreferredBackBufferHeight - 40), Color.Silver);
                    break;

                case GameState.Playing:
                    //spriteBatch.DrawString(_defaultFont, "current= " + GraphicsDevice.Viewport.Width + "," + GraphicsDevice.Viewport.Height, Vector2.Zero, Color.White);
                    foreach (var shot in _shots) { spriteBatch.Draw(_greenShot, shot, Color.White); }

                    foreach (var shot in _enemyShots) { spriteBatch.Draw(_redShot, shot, Color.White); }

                    foreach (var enemy in _enemies) { enemy.Draw(gameTime); }

                    DrawLife(_livesLeft);

                    spriteBatch.DrawString(_defaultFont, string.Format("{0:000000} points", _spaceship.Points), new Vector2(graphics.PreferredBackBufferWidth - 250, 20), Color.White);

                    break;

                case GameState.GameOver:
                    spriteBatch.DrawString(_defaultFont, "GAME OVER", new Vector2(GraphicsDevice.Viewport.Width / 2 - 100, _halfScreenHeight - 60), Color.White);
                    spriteBatch.DrawString(_smallFont, _spaceship.Points + " points", new Vector2(390, _halfScreenHeight), Color.White);
                    spriteBatch.DrawString(_smallFont, "[ENTER] for new game", new Vector2(graphics.PreferredBackBufferWidth - 300, graphics.PreferredBackBufferHeight - 40), Color.Silver);
                    break;
            }

            spriteBatch.End();
        }

        private void DrawLife(int livesLeft)
        {
            for (int i = 0; i < livesLeft; i++)
            {
                spriteBatch.Draw(_life, new Vector2(20 + i * 50, 20), Color.White);
            }

        }

        bool WasJustPressed(Keys keyToCheck)
        {
            return _oldKeyboard.IsKeyUp(keyToCheck) && _currentKeyboard.IsKeyDown(keyToCheck);
        }

        void Fire()
        {
            if (_spaceship.Guns > 1)
            {
                _spaceship.ShotsFired += 2;
                _shots.Add(_spaceship.Position - Vector2.UnitY * 40 - Vector2.UnitX * 50);
                _shots.Add(_spaceship.Position - Vector2.UnitY * 40 + Vector2.UnitX * 40);
            }
            if (_spaceship.Guns % 2 == 1)
            {
                _spaceship.ShotsFired++;
                _shots.Add(_spaceship.Position - Vector2.UnitY * 40 - Vector2.UnitX * 4);
            }
           _laserSound.Play();
        }

        void EnemyFire(Sprite enemy)
        {
            _enemyShots.Add(enemy.Position + Vector2.UnitY * 30);

            _enemyLaserSound.Play();
        }

        void AddExplosion(Vector2 position, Texture2D texture)
        {
            Components.Add(new Explosion(this, texture, position, spriteBatch));
            _explosionSound.Play();
        }

        void NextLife()
        {
            _livesLeft--;
            if (_livesLeft >= 0)
            {
                _spaceship.InvincibleTimeLeft = 2000;
                _spaceship.Guns = 1;
                _spaceship.HasShield = false;
                _spaceship.Position = new Vector2(WindowSize.X / 2, WindowSize.Y - 50);
            }
        }

        void NewGame()
        {
            Components.Clear();
            _enemies.Clear();
            _livesLeft = 3;
            _wave = 0;
            _spaceship = new PlayerSpaceShip(this, Content.Load<Texture2D>("Player ship"),
                Content.Load<Texture2D>("Player ship"),
                Content.Load<Texture2D>("Player ship"), Content.Load<Texture2D>("shield"),
                new Vector2(WindowSize.X / 2, WindowSize.Y - 50), spriteBatch);
            Components.Add(_spaceship);
            _borderOverSpaceship = _halfScreenHeight - _spaceship.Texture.Height;
            _mineController = new MineController(this, graphics, spriteBatch, 3000, 9000);
            Components.Add(_mineController);


            _textController = new TextController(this, graphics, spriteBatch, Color.LightGreen, _defaultFont, "10 points", "25 points", "100 points", "500 points");
            Components.Add(_textController);

        }


        private Texture2D CreateBackground()
        {

            RenderTarget2D target = new RenderTarget2D(this.GraphicsDevice, 2048, 2048);
            //tell the GraphicsDevice we want to render to the gamesMenu rendertarget (an in-memory buffer)
            GraphicsDevice.SetRenderTarget(target);

            //clear the background
            GraphicsDevice.Clear(Color.Transparent);

            //begin drawing
            spriteBatch.Begin();
            for (int x = 0; x < 8; x++)
            {
                for (int y = 0; y < 8; y++)
                {
                    spriteBatch.Draw(Content.Load<Texture2D>("starfield1"), new Vector2(x * 256, y * 256), Color.White);
                }
            }

            spriteBatch.End();
            //reset the GraphicsDevice to draw on the backbuffer (directly to the backbuffer)
            GraphicsDevice.SetRenderTarget(null);

            return target;//.GetAsTextureCopy(GraphicsDevice);
        }
    }
}