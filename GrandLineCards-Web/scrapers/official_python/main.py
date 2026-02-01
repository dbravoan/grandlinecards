
import argparse
import os
import json
import requests
from scraper import OfficialScraper
import time

def download_image(url, save_path):
    if os.path.exists(save_path):
        # Skip if already exists
        return
        
    try:
        response = requests.get(url, stream=True, timeout=10)
        if response.status_code == 200:
            os.makedirs(os.path.dirname(save_path), exist_ok=True)
            with open(save_path, 'wb') as f:
                for chunk in response.iter_content(1024):
                    f.write(chunk)
            print(f"Downloaded: {save_path}")
        else:
            print(f"Failed to download {url}: Status {response.status_code}")
    except Exception as e:
        print(f"Error downloading {url}: {e}")

def main():
    parser = argparse.ArgumentParser(description="One Piece TCG Official Scraper")
    parser.add_argument('--download-images', action='store_true', help="Download card images")
    parser.add_argument('--output-dir', type=str, default='./data', help="Output directory for json and images")
    parser.add_argument('--dry-run', action='store_true', help="Run without downloading full data (fetches 1 series)")
    
    args = parser.parse_args()
    
    scraper = OfficialScraper()
    
    # 1. Get All Series
    print("Fetching series list...")
    series_map = scraper.get_all_series()
    print(f"Found {len(series_map)} series.")
    
    all_cards = []
    
    for code, series_id in series_map.items():
        print(f"Processing Series: {code} (ID: {series_id})")
        cards = scraper.scrape_series(series_id)
        all_cards.extend(cards)
        print(f"  > Found {len(cards)} cards.")
        
        if args.dry_run:
            print("Dry run active, stopping after first series.")
            break
            
        # Be polite to the server
        time.sleep(1)
        
    # 2. Save JSON
    os.makedirs(args.output_dir, exist_ok=True)
    json_path = os.path.join(args.output_dir, 'scraped_cards.json')
    with open(json_path, 'w', encoding='utf-8') as f:
        json.dump(all_cards, f, indent=4, ensure_ascii=False)
    print(f"Saved {len(all_cards)} cards to {json_path}")
    
    # 3. Download Images
    if args.download_images:
        print("Starting image download...")
        image_base_dir = args.output_dir 
        # Plan says: storage/app/public/cards/{set_id}/{card_id}.png
        # If output-dir is passed as the root (e.g. storage/app/public/? No, user passes a data dir usually)
        # But for "Ralph Loop", we might want to write directly to storage location or a staging area.
        # Let's assume output-dir is the root where 'cards' folder will be created if we want strict structure,
        # OR we can just use the structure defined in the plan: {output_dir}/cards/{set}/{id}.png
        
        for card in all_cards:
            if not card.get('imageUrl'):
                continue
                
            card_id = card['id']
            set_id = card['setId']
            
            # Sanitization
            filename = f"{card_id}.png"
            # Ensure folder structure matches Laravel expectation: public/cards/{set_id}/{card_id}.png
            # We will create a 'cards' subdir inside output_dir to be safe and organized
            
            save_path = os.path.join(image_base_dir, 'cards', set_id, filename)
            download_image(card['imageUrl'], save_path)
            
    print("Done.")

if __name__ == "__main__":
    main()
