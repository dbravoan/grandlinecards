
import requests
from bs4 import BeautifulSoup
import re
import time

class OfficialScraper:
    BASE_URL = "https://en.onepiece-cardgame.com/cardlist/"
    
    def __init__(self):
        self.session = requests.Session()
        self.session.headers.update({
            'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
        })

    def get_all_series(self):
        """
        Returns a dictionary of available series codes and their internal ID values.
        Example: {'OP-01': '569101', ...}
        """
        response = self.session.get(self.BASE_URL)
        response.raise_for_status()
        
        soup = BeautifulSoup(response.content, 'html.parser')
        series_map = {}
        
        # The filter is a select with name="series"
        select = soup.find('select', {'name': 'series'})
        if select:
            options = select.find_all('option')
            for option in options:
                value = option.get('value')
                text = option.get_text()
                
                # Text format: "BOOSTER PACK -Romance Dawn- [OP-01]"
                match = re.search(r'\[([A-Z0-9]+-[0-9]+)\]', text)
                if match:
                    code = match.group(1)
                    series_map[code] = value
                elif 'promotion' in text.lower():
                     # Handle generic promotion/misc if needed, though they usually have codes too
                     pass
        
        return series_map

    def scrape_series(self, series_id):
        """
        Scrapes all cards for a specific series ID.
        Returns a list of dictionaries.
        """
        url = f"{self.BASE_URL}?series={series_id}"
        print(f"Scraping URL: {url}")
        
        response = self.session.get(url)
        response.raise_for_status()
        
        soup = BeautifulSoup(response.content, 'html.parser')
        cards = []
        
        # Site logic: A generic list of .modalOpen links, and corresponding DLs with data.
        # However, the dl is usually hidden or part of the modal structure.
        # In the PHP scraper, we saw `crawler->filter('.modalOpen')` then finding `#id`.
        
        modal_links = soup.select('.modalOpen')
        
        for link in modal_links:
            # data-src="#OP01-001" or similar
            data_src = link.get('data-src')
            if not data_src:
                continue
                
            card_id = data_src.lstrip('#')
            
            # Find the corresponding data container
            # It's a dl with id matching the card_id
            data_node = soup.find(id=card_id)
            
            if not data_node:
                continue
                
            # Extract Data
            # Name
            name_node = data_node.find(class_='cardName')
            name = name_node.get_text(strip=True) if name_node else "Unknown"
            
            # Info Col: "OP01-001 | L | LEADER"
            info_col_node = data_node.find(class_='infoCol')
            info_text = info_col_node.get_text() if info_col_node else ""
            parts = [p.strip() for p in info_text.split('|')]
            
            rarity = parts[1] if len(parts) > 1 else "Unknown"
            card_type = parts[2] if len(parts) > 2 else "Unknown"
            
            # Attributes / Color
            color_node = data_node.find(class_='color')
            color = color_node.get_text(strip=True).replace('Color', '') if color_node else "Unknown"
            
            # Stats
            def extract_int(node_class):
                node = data_node.find(class_=node_class)
                if not node:
                    return None
                text = node.get_text(strip=True)
                # Remove generic text, keep numbers (sometimes it has "Cost", "Power" label?)
                # Usually text is just the number but let's be safe
                nums = re.findall(r'\d+', text)
                return int(nums[0]) if nums else None

            cost = extract_int('cost')
            power = extract_int('power')
            counter = extract_int('counter')
            
            # Life (Leader only usually) - The PHP scraper missed this, adding it for completeness
            life = extract_int('life') 
            
            # Feature / Attributes
            feature_node = data_node.find(class_='feature')
            feature_text = feature_node.get_text(strip=True).replace('Type', '') if feature_node else ""
            attributes = [a.strip() for a in feature_text.split('/') if a.strip()]
            
            # Effect Text
            text_node = data_node.find(class_='text')
            effect_text = ""
            if text_node:
                # We want to preserve visual breaks if possible, but usually text is fine
                # BS4 .get_text(separator="\n") is good
                effect_text = text_node.get_text(separator="\n", strip=True) .replace('Effect', '', 1).strip()

            # Trigger Text - Not explicitly separated in PHP scraper, but let's see if it exists
            # Sometimes it's a separate section or highlighted text. 
            # For now, we follow the PHP scraper which put it all in effectText or didn't check
            # We will try to find a trigger specific block if it exists
            trigger_text = None
            
            # Image URL
            img_tag = link.find('img')
            image_url = ""
            if img_tag:
               src = img_tag.get('src')
               if src:
                   if src.startswith('..'):
                       image_url = "https://en.onepiece-cardgame.com" + src[2:]
                   elif src.startswith('http'):
                       image_url = src
                   else:
                        image_url = "https://en.onepiece-cardgame.com" + src

            card_data = {
                "id": card_id,
                "setId": card_id.split('-')[0] if '-' in card_id else "PROMO",
                "name": name,
                "rarity": rarity,
                "color": color,
                "type": card_type,
                "cost": cost,
                "power": power,
                "counter": counter,
                "life": life,
                "attributes": attributes,
                "effectText": effect_text,
                "triggerText": trigger_text,
                "imageUrl": image_url
            }
            
            cards.append(card_data)
        
        return cards
