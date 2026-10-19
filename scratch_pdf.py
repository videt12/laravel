import re
import zlib

pdf_path = r"C:\Users\NTC COMPUTER\.gemini\antigravity-ide\brain\5a7071f7-16a5-4da7-8d98-86ba989ab48d\.user_uploaded\media_1789438815719.pdf"

with open(pdf_path, 'rb') as f:
    content = f.read()

streams = re.findall(rb'stream[\r\n]+(.*?)[\r\n]+endstream', content, re.DOTALL)
print(f"Found {len(streams)} streams")

extracted_text = []
for s in streams:
    try:
        decompressed = zlib.decompress(s)
        # find text in TJ or Tj operators
        # or just find utf-8 strings
        text_matches = re.findall(rb'\((.*?)\)\s*Tj', decompressed)
        for tm in text_matches:
            try:
                extracted_text.append(tm.decode('utf-8'))
            except:
                try:
                    extracted_text.append(tm.decode('utf-16-be'))
                except:
                    pass
        # also check raw strings in decompressed
        for line in decompressed.splitlines():
            if b'BT' in line or b'ET' in line or b'Tj' in line:
                extracted_text.append(line.decode('latin1', errors='ignore'))
    except Exception as e:
        pass

output = "\n".join(extracted_text[:100])
print(output[:3000])
