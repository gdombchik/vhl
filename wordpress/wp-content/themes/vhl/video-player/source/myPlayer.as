/*
╠═ f4.Player Sample ═══════════════════════════════════════════════════════
  Software: f4.Player - flash video player sample
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
package {
	import f4.Player;
	import flash.display.MovieClip;
	import flash.display.StageAlign;
    import flash.display.StageScaleMode;
    import flash.display.Loader;
	import flash.events.Event;
	import flash.events.ContextMenuEvent;
	import flash.net.URLRequest;
	import flash.net.navigateToURL;
	import flash.ui.ContextMenu;
	import flash.ui.ContextMenuItem;
	public class myPlayer extends MovieClip {
		public function myPlayer() {
			stage.scaleMode = StageScaleMode.NO_SCALE;
			stage.align = StageAlign.TOP_LEFT;
			var video = this.loaderInfo.parameters.video;
			var thumbnail = this.loaderInfo.parameters.thumbnail;
			var skinfile = this.loaderInfo.parameters.skin;
			var autoplay = this.loaderInfo.parameters.autoplay == 1;
			var fullscreen = this.loaderInfo.parameters.fullscreen;
			var skin;
			var skinEvent:Function = function(e:Event):void {
				skin = e.currentTarget.content;
				stage.addChild(skin);
				skin.initialization(
						stage.stageWidth,
						stage.stageHeight,
						new Player(),
						video ? video : 'http://ttreklamweb.com/data/produksiyon/m212923375107452.mp4?12d4',//'videos/андреа.mp4',
						thumbnail ? thumbnail : 'videos/video-thumbnail.jpg',
						autoplay,  // autoplay
						fullscreen // fullscreen button
						);
			}
			var sl = new Loader();
			sl.contentLoaderInfo.addEventListener(Event.COMPLETE, skinEvent);
			sl.load(new URLRequest(skinfile ? skinfile : 'skins/mySkin.swf'));
			// Resize Event
			var resizeEvent:Function = function(e:Event):void {
				skin.pose(stage.stageWidth,stage.stageHeight);
				ExternalInterface.call("console.log",(stage.stageWidth.toString()+'x'+stage.stageHeight.toString()));
			}
			stage.addEventListener(Event.RESIZE, resizeEvent);
			stage.addEventListener(Event.FULLSCREEN, resizeEvent);
			// CUSTOMIZE RIGHT CLICK CONTEXT MENU
			var menu:ContextMenu = new ContextMenu();
			menu.hideBuiltInItems();
			var signature = new ContextMenuItem("f4Player");
			function openLink(e:ContextMenuEvent):void{
				navigateToURL(new URLRequest("http://f4player.org/?feature=player"));
			}
			signature.addEventListener(ContextMenuEvent.MENU_ITEM_SELECT, openLink);
			menu.customItems.push(signature);
			contextMenu = menu;
		}		
	}
}