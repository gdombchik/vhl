/*
╠═ f4.Player ══════════════════════════════════════════════════════════════
  Software: f4.Player - flash video player
   Version: beta 1.0
   Support: http://f4player.org
    Author: f4OS
   Contact: http://f4player.org
 -------------------------------------------------------------------------
   License: Distributed under the Lesser General Public License (LGPL)
            http://www.gnu.org/copyleft/lesser.html
 This program is distributed in the hope that it will be useful - WITHOUT
 ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 FITNESS FOR A PARTICULAR PURPOSE.
═══════════════════════════════════════════════════════════════════════════ */
package f4 {
	
	import flash.display.MovieClip;
	import flash.display.Loader;
	import flash.display.Stage;
	import flash.display.StageDisplayState;
	import flash.display.Bitmap;
	
	import flash.net.NetConnection;
	import flash.net.NetStream;	
	import flash.net.URLLoader;
	import flash.net.URLRequest;
	import flash.media.Video;
	import flash.media.SoundTransform
	import flash.events.NetStatusEvent;
	import flash.events.TimerEvent;
	import flash.events.Event;
	import flash.utils.Timer;
	
	import flash.external.ExternalInterface;
	
	public class Player extends MovieClip implements PlayerInterface {
		
		public var body:MovieClip;
		private var ns:NetStream;
		private var v:Video;
		private var st:SoundTransform;
		private var togglepause:Boolean = false;
		private var volcache:int = 0;
		private var duration:int;
		private var videoWidth:int;
		private var videoHeight:int;
		private var status:String;
		public var callback:Function;
		
		public function Player() {
			body = new MovieClip();
			body.graphics.drawRoundRect(0, 0, 160, 90, 0, 0);
			
			var c:Object = new Object();
			var metaDO:Object = function(d:Object):void {
				duration = d.duration;
				videoWidth = d.width;				
				videoHeight = d.height;
			}
			c.onMetaData = metaDO;
			var nc:NetConnection = new NetConnection();
            nc.connect(null);
			ns = new NetStream(nc);
			var nsEvent:Function = function(e:NetStatusEvent):void {
				//this.Log(e.info.code);
				if(status != e.info.code){
					/*switch (e.info.code) {
						case "NetStream.Play.Start" :
							trace('playing');
							//playingBarTimer.start();
						break;
						case "NetStream.Buffer.Empty" :
						break;
						case "NetStream.Buffer.Full" :
						break;
						case "NetStream.Buffer.Flush" :
							//bufferFlush = true;
						break;
						case "NetStream.Seek.Notify" :
							//invalidTime = false;				
						break;
						case "NetStream.Seek.InvalidTime" :
							//bufferFlush = false;
							//invalidTime = true;						
						break;
						case "NetStream.Play.Stop" :
							//if(bufferFlush) STOP();			
						break;
					}*/
					status = e.info.code;
				}
				callback(Info());
			}
			ns.addEventListener(NetStatusEvent.NET_STATUS, nsEvent);
			ns.bufferTime = 5; // buffer time 5 sec.
			ns.client = c;	
			
			st = new SoundTransform(); 
			
        }
		/*public function Set(settings:Object):void {
			this.Log('Set');
			video.x = settings.x;
			video.y = settings.y;
			video.width = settings.width;
			video.height = settings.height;			
        }*/
		public function Callback(callback:Function):void {
			this.callback = callback;
			var timer:Timer = new Timer(100);
			var timerEvent = function(e:TimerEvent):void {
				var info:Object = Info();
				if(info.progress >= 100) timer.stop();
				callback(info);
			}
			timer.addEventListener(TimerEvent.TIMER, timerEvent);
			timer.start();
		}
		private function Info():Object {
			var playing:Number = ( ns.time / duration ).toFixed(2);
			return {
				'width': videoWidth,
				'height': videoHeight,
				'total': ns.bytesTotal,
				'loaded': ns.bytesLoaded,
				'progress': ( ns.bytesLoaded / ns.bytesTotal ).toFixed(2),
				'duration': duration,
				'time': ns.time,
				'playing': ( playing > 1 ? 1 : playing ),
				'status': status
				};
        }
		public function Movie(w:int,h:int):Video {
			this.Log('Video dimension: '+w.toString()+'x'+h.toString());
			v = new Video(w,h);	
			v.smoothing = true;			
			v.attachNetStream(ns);
			return v;
		}
		
		public function Play(file:String):Boolean {
			this.Log('Play: '+file);
			ns.play(file);
			return true;
        }
		public function Pause():Boolean {
			this.Log('Pause');
			if (togglepause) {
				togglepause = false;
				ns.resume();
			} else {
				togglepause = true;
				ns.pause();
			}
			return togglepause;
        }
		public function Stop():void {
			this.Log('Stop');
			ns.close();
        }
		public function Volume(vol:Number) {
			this.Log('Volume: '+vol.toString());
			st.volume = vol;
			ns.soundTransform = st;
        }
		public function Mute():int {
			if (volcache) {
				this.Log('VolumeCache: '+volcache.toString());
				st.volume = volcache;
				ns.soundTransform = st;
			} else {
				this.Log('Mute');
				volcache = st.volume;
				st.volume = 0;
				ns.soundTransform = st;
			}
			return st.volume;
        }
		public function Fullscreen(stage:Stage):Boolean {
			this.Log('Fullscreen: '+!(stage.displayState == StageDisplayState.FULL_SCREEN));
			if(stage.displayState == StageDisplayState.FULL_SCREEN){
				stage.displayState = StageDisplayState.NORMAL;
				return false;
			} else {
				stage.displayState = StageDisplayState.FULL_SCREEN;
				return true;
			}
        }
		public function Seek(point:int):int {
			this.Log('Seek: '+point.toString());
			ns.seek(point);
        }
		public function Thumbnail(image:String,w:int,h:int):MovieClip {
			this.Log('Thumbnail: '+image);
			var mc:MovieClip = new MovieClip();
			var ss = new Loader();
			var ssEvent:Function = function(event:Event):void {
				var image:Bitmap = ss.content;
				image.width = w;	
				image.height = h;
				mc.addChild(image);
			}
			ss.contentLoaderInfo.addEventListener(Event.COMPLETE, ssEvent);
			ss.load(new URLRequest(image));
			return mc;
        }
		public function Next():void {
			this.Log('Next');
        }
		public function Prev():void {
			this.Log('Prev');
        }
		public function Subtitle(sub:String):void {
			this.Log('Subtitle: '+sub);
        }
		public function Log(log:String):void {
			trace(log);
			ExternalInterface.call("console.log",log);
        }
	}
}
