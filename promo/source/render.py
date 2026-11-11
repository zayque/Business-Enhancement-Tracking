"""
Render promo/source/video.html to a 1080x1920 30fps MP4 (no audio).

  python -I promo/source/render.py preview 1.5 5 10 ...   -> PNG stills in promo/source/preview/
  python -I promo/source/render.py full                    -> promo/trackwyn-promo.mp4
"""
import json, subprocess, sys, time
from pathlib import Path
import imageio_ffmpeg
from playwright.sync_api import sync_playwright

SRC = Path(__file__).resolve().parent
OUT = SRC.parent / "trackwyn-promo.mp4"
FPS, DURATION = 30, 45.0


def open_page(pw):
    browser = pw.chromium.launch(args=["--disable-gpu-vsync"])
    page = browser.new_page(viewport={"width": 1080, "height": 1920}, device_scale_factor=1)
    boxes = (SRC / "assets" / "boxes.json").read_text()
    page.add_init_script(f"window.BOXES = {boxes};")
    page.goto((SRC / "video.html").as_uri())
    page.wait_for_load_state("networkidle")
    page.evaluate("window.ready")
    page.evaluate("render(0)")
    return browser, page


def frame(page, t, path=None):
    page.evaluate(f"render({t})")
    return page.screenshot(type="png" if path else "jpeg", quality=None if path else 95, path=path)


def main():
    mode = sys.argv[1] if len(sys.argv) > 1 else "full"
    with sync_playwright() as pw:
        browser, page = open_page(pw)
        if mode == "preview":
            out = SRC / "preview"; out.mkdir(exist_ok=True)
            for t in map(float, sys.argv[2:]):
                frame(page, t, str(out / f"t{t:05.2f}.png")); print("frame", t)
        else:
            n = int(FPS * DURATION)
            cmd = [imageio_ffmpeg.get_ffmpeg_exe(), "-y", "-loglevel", "error", "-f", "image2pipe", "-framerate", str(FPS),
                   "-c:v", "mjpeg", "-i", "-", "-c:v", "libx264", "-preset", "slow", "-crf", "17",
                   "-pix_fmt", "yuv420p", "-r", str(FPS), "-movflags", "+faststart", "-an", str(OUT)]
            ff = subprocess.Popen(cmd, stdin=subprocess.PIPE)
            start = time.time()
            for i in range(n):
                ff.stdin.write(frame(page, i / FPS))
                if i % 150 == 0:
                    print(f"{i}/{n} frames, {time.time() - start:.0f}s", flush=True)
            ff.stdin.close(); ff.wait()
            print("wrote", OUT, f"in {time.time() - start:.0f}s")
        browser.close()


if __name__ == "__main__":
    main()
