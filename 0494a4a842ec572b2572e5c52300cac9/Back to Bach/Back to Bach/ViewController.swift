//
//  ViewController.swift
//  Back to Bach
//
//  Created by Matthew Connors on 9/9/16.
//  Copyright © 2016 Matt Connors. All rights reserved.
//

import UIKit
import AVFoundation

class ViewController: UIViewController {
    
    var player = AVAudioPlayer()
    var timer = Timer()
    
    // BUTTONS!
    
    func updateScrubber() {
        
        scrubSlider.value = Float(player.currentTime)
        
    }
    
    @IBAction func play(_ sender: AnyObject) {
        
        player.play()
        
        timer = Timer.scheduledTimer(timeInterval: 1, target: self, selector: #selector(ViewController.updateScrubber), userInfo: nil, repeats: true)
        
    }
    @IBAction func pause(_ sender: AnyObject) {
        
        player.pause()
        timer.invalidate()
        
    }
    @IBAction func stop(_ sender: AnyObject) {
        
        timer.invalidate()
        player.stop()
        player.currentTime = 0
        scrubSlider.value = 0
    }
    
    // SLIDERS!!
    
    @IBOutlet weak var volumeSlider: UISlider!
    @IBOutlet weak var scrubSlider: UISlider!
    
    @IBAction func volumeSliderMoved(_ sender: AnyObject) {
        
        player.volume = volumeSlider.value
        
    }
    @IBAction func scrubSliderMoved(_ sender: AnyObject) {
        
        player.currentTime = TimeInterval(scrubSlider.value)
        
    }
    

    @IBOutlet weak var carImage: UIImageView!
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
        
        let audioPath = Bundle.main.path(forResource: "sheep", ofType: "mp3")
        
        do {
            
            try player = AVAudioPlayer(contentsOf: URL(fileURLWithPath: audioPath!))
            
            scrubSlider.maximumValue = Float(player.duration)
            
        } catch {
            
            // Procees any errors
            
        }
        
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }


}

