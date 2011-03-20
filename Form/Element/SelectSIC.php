<?php
/**
 * Leek - Zend Framework Components
 *
 * @category   Leek
 * @package    Leek_Form
 * @subpackage Element
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version    $Id: SelectCountry.php 52 2009-06-02 22:11:31Z leeked $
 */

/**
 * Leek_Form
 *
 * @category   Leek
 * @package    Leek_Form
 * @subpackage Element
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_Form_Element_SelectSIC extends Zend_Form_Element_Select
{
    /**
     * List of SIC codes
     *
     * @var array
     */
    private $_sics = array(
        100  => 'Agricultural Production-Crops',
        200  => 'Agricultural Prod-Livestock & Animal Specialties',
        700  => 'Agricultural Services',
        800  => 'Forestry',
        900  => 'Fishing, Hunting and Trapping',
        1000 => 'Metal Mining',
        1040 => 'Gold and Silver Ores',
        1090 => 'Miscellaneous Metal Ores',
        1221 => 'Bituminous Coal & Lignite Mining',
        1311 => 'Crude Petroleum & Natural Gas',
        1381 => 'Drilling Oil & Gas Wells',
        1382 => 'Oil & Gas Field Exploration Services',
        1389 => 'Oil & Gas Field Services, NEC',
        1400 => 'Mining & Quarrying of Nonmetallic Minerals (No Fuels)',
        1520 => 'General Bldg Contractors - Residential Bldgs',
        1531 => 'Operative Builders',
        1540 => 'General Bldg Contractors - Nonresidential Bldgs',
        1600 => 'Heavy Construction Other Than Bldg Const - Contractors',
        1623 => 'Water, Sewer, Pipeline, Comm & Power Line Construction',
        1700 => 'Construction - Special Trade Contractors',
        1731 => 'Electrical Work',
        2000 => 'Food and Kindred Products',
        2011 => 'Meat Packing Plants',
        2013 => 'Sausages & Other Prepared Meat Products',
        2015 => 'Poultry Slaughtering and Processing',
        2020 => 'Dairy Products',
        2024 => 'Ice Cream & Frozen Desserts',
        2030 => 'Canned, Frozen & Preserved Fruit, Veg & Food Specialties',
        2033 => 'Canned, Fruits, Veg, Preserves, Jams & Jellies',
        2040 => 'Grain Mill Products',
        2050 => 'Bakery Products',
        2052 => 'Cookies & Crackers',
        2060 => 'Sugar & Confectionery Products',
        2070 => 'Fats & Oils',
        2080 => 'Beverages',
        2082 => 'Malt Beverages',
        2086 => 'Bottled & Canned Soft Drinks & Carbonated Waters',
        2090 => 'Miscellaneous Food Preparations & Kindred Products',
        2092 => 'Prepared Fresh or Frozen Fish & Seafood',
        2100 => 'Tobacco Products',
        2111 => 'Cigarettes',
        2200 => 'Textile Mill Products',
        2211 => 'Broadwoven Fabric Mills, Cotton',
        2221 => 'Broadwoven Fabric Mills, Man Made Fiber & Silk',
        2250 => 'Knitting Mills',
        2253 => 'Knit Outerwear Mills',
        2273 => 'Carpets & Rugs',
        2300 => 'Apparel & Other Finished Prods of Fabrics & Similar Matl',
        2320 => 'Men\'s & Boys\' Furnishings, Work Clothing, & Allied Garments',
        2330 => 'Women\'s, Misses\', and Juniors Outerwear',
        2340 => 'Women\'s, Misses\', Children\'s & Infant\'s Undergarments',
        2390 => 'Miscellaneous Fabricated Textile Products',
        2400 => 'Lumber & Wood Products (No Furniture)',
        2421 => 'Sawmills & Planting Mills, General',
        2430 => 'Millwood, Veneer, Plywood, & Structural Wood Members',
        2451 => 'Mobile Homes',
        2452 => 'Prefabricated Wood Bldgs & Components',
        2510 => 'Household Furniture',
        2511 => 'Wood Household Furniture, (No Upholstered)',
        2520 => 'Office Furniture',
        2522 => 'Office Furniture (No Wood)',
        2531 => 'Public Bldg & Related Furniture',
        2540 => 'Partitions, Shelvg, Lockers, & office & Store Fixtures',
        2590 => 'Miscellaneous Furniture & Fixtures',
        2600 => 'Papers & Allied Products',
        2611 => 'Pulp Mills',
        2621 => 'Paper Mills',
        2631 => 'Paperboard Mills',
        2650 => 'Paperboard Containers & Boxes',
        2670 => 'Converted Paper & Paperboard Prods (No Containers/Boxes)',
        2673 => 'Plastics, Foil & Coated Paper Bags',
        2711 => 'Newspapers: Publishing or Publishing & Printing',
        2721 => 'Periodicals: Publishing or Publishing & Printing',
        2731 => 'Books: Publishing or Publishing & Printing',
        2732 => 'Book Printing',
        2741 => 'Miscellaneous Publishing',
        2750 => 'Commercial Printing',
        2761 => 'Manifold Business Forms',
        2771 => 'Greeting Cards',
        2780 => 'Blankbooks, Looseleaf Binders & Bookbindg & Related Work',
        2790 => 'Service Industries For The Printing Trade',
        2800 => 'Chemicals & Allied Products',
        2810 => 'Industrial Inorganic Chemicals',
        2820 => 'Plastic Material, Synth Resin/Rubber, Cellulos (No Glass)',
        2821 => 'Plastic Materials, Synth Resins & Nonvulcan Elastomers',
        2833 => 'Medicinal Chemicals & Botanical Products',
        2834 => 'Pharmaceutical Preparations',
        2835 => 'In Vitro & In Vivo Diagnostic Substances',
        2836 => 'Biological Products, (No Diagnostic Substances)',
        2840 => 'Soap, Detergents, Cleaning Preparations, Perfumes, Cosmetics',
        2842 => 'Specialty Cleaning, Polishing and Sanitation Preparations',
        2844 => 'Perfumes, Cosmetics & Other Toilet Preparations',
        2851 => 'Paints, Varnishes, Lacquers, Enamels & Allied Prods',
        2860 => 'Industrial Organic Chemicals',
        2870 => 'Agricultural Chemicals',
        2890 => 'Miscellaneous Chemical Products',
        2891 => 'Adhesives & Sealants',
        2911 => 'Petroleum Refining',
        2950 => 'Asphalt Paving & Roofing Materials',
        2990 => 'Miscellaneous Products of Petroleum & Coal',
        3011 => 'Tires & Inner Tubes',
        3021 => 'Rubber & Plastics Footwear',
        3050 => 'Gaskets, Packg & Sealg Devices & Rubber & Plastics Hose',
        3060 => 'Fabricated Rubber Products, NEC',
        3080 => 'Miscellaneous Plastics Products',
        3081 => 'Unsupported Plastics Film & Sheet',
        3086 => 'Plastics Foam Products',
        3089 => 'Plastics Products, NEC',
        3100 => 'Leather & Leather Products',
        3140 => 'Footwear, (No Rubber)',
        3211 => 'Flat Glass',
        3220 => 'Glass & Glassware, Pressed or Blown',
        3221 => 'Glass Containers',
        3231 => 'Glass Products, Made of Purchased Glass',
        3241 => 'Cement, Hydraulic',
        3250 => 'Structural Clay Products',
        3260 => 'Pottery & Related Products',
        3270 => 'Concrete, Gypsum & Plaster Products',
        3272 => 'Concrete Products, Except Block & Brick',
        3281 => 'Cut Stone & Stone Products',
        3290 => 'Abrasive, Asbestos & Misc Nonmetallic Mineral Prods',
        3310 => 'Steel Works, Blast Furnaces & Rolling & Finishing Mills',
        3312 => 'Steel Works, Blast Furnaces & Rolling Mills (Coke Ovens)',
        3317 => 'Steel Pipe & Tubes',
        3320 => 'Iron & Steel Foundries',
        3330 => 'Primary Smelting & Refining of Nonferrous Metals',
        3334 => 'Primary Production of Aluminum',
        3341 => 'Secondary Smelting & Refining of Nonferrous Metals',
        3350 => 'Rolling Drawing & Extruding of Nonferrous Metals',
        3357 => 'Drawing & Insulating of Nonferrous Wire',
        3360 => 'Nonferrous Foundries (Castings)',
        3390 => 'Miscellaneous Primary Metal Products',
        3411 => 'Metal Cans',
        3412 => 'Metal Shipping Barrels, Drums, Kegs & Pails',
        3420 => 'Cutlery, Handtools & General Hardware',
        3430 => 'Heating Equip, Except Elec & Warm Air; & Plumbing Fixtures',
        3433 => 'Heating Equipment, Except Electric & Warm Air Furnaces',
        3440 => 'Fabricated Structural Metal Products',
        3442 => 'Metal Doors, Sash, Frames, Moldings & Trim',
        3443 => 'Fabricated Plate Work (Boiler Shops)',
        3444 => 'Sheet Metal Work',
        3448 => 'Prefabricated Metal Buildings & Components',
        3451 => 'Screw Machine Products',
        3452 => 'Bolts, Nuts, Screws, Rivets & Washers',
        3460 => 'Metal Forgings & Stampings',
        3470 => 'Coating, Engraving & Allied Services',
        3480 => 'Ordnance & Accessories, (No Vehicles/Guided Missiles)',
        3490 => 'Miscellaneous Fabricated Metal Products',
        3510 => 'Engines & Turbines',
        3523 => 'Farm Machinery & Equipment',
        3524 => 'Lawn & Garden Tractors & Home Lawn & Gardens Equip',
        3530 => 'Construction, Mining & Materials Handling Machinery & Equip',
        3531 => 'Construction Machinery & Equip',
        3532 => 'Mining Machinery & Equip (No Oil & Gas Field Mach & Equip)',
        3533 => 'Oil & Gas Field Machinery & Equipment',
        3537 => 'Industrial Trucks, Tractors, Trailers & Stackers',
        3540 => 'Metalworkg Machinery & Equipment',
        3541 => 'Machine Tools, Metal Cutting Types',
        3550 => 'Special Industry Machinery (No Metalworking Machinery)',
        3555 => 'Printing Trades Machinery & Equipment',
        3559 => 'Special Industry Machinery, NEC',
        3560 => 'General Industrial Machinery & Equipment',
        3561 => 'Pumps & Pumping Equipment',
        3562 => 'Ball & Roller Bearings',
        3564 => 'Industrial & Commercial Fans & Blowers & Air Purifying Equip',
        3567 => 'Industrial Process Furnaces & Ovens',
        3569 => 'General Industrial Machinery & Equipment, NEC',
        3570 => 'Computer & office Equipment',
        3571 => 'Electronic Computers',
        3572 => 'Computer Storage Devices',
        3575 => 'Computer Terminals',
        3576 => 'Computer Communications Equipment',
        3577 => 'Computer Peripheral Equipment, NEC',
        3578 => 'Calculating & Accounting Machines (No Electronic Computers)',
        3579 => 'Office Machines, NEC',
        3580 => 'Refrigeration & Service Industry Machinery',
        3585 => 'Air-Cond & Warm Air Heatg Equip & Comm & Indl Refrig Equip',
        3590 => 'Misc Industrial & Commercial Machinery & Equipment',
        3600 => 'Electronic & Other Electrical Equipment (No Computer Equip)',
        3612 => 'Power, Distribution & Specialty Transformers',
        3613 => 'Switchgear & Switchboard Apparatus',
        3620 => 'Electrical Industrial Apparatus',
        3621 => 'Motors & Generators',
        3630 => 'Household Appliances',
        3634 => 'Electric Housewares & Fans',
        3640 => 'Electric Lighting & Wiring Equipment',
        3651 => 'Household Audio & Video Equipment',
        3652 => 'Phonograph Records & Prerecorded Audio Tapes & Disks',
        3661 => 'Telephone & Telegraph Apparatus',
        3663 => 'Radio & Tv Broadcasting & Communications Equipment',
        3669 => 'Communications Equipment, NEC',
        3670 => 'Electronic Components & Accessories',
        3672 => 'Printed Circuit Boards',
        3674 => 'Semiconductors & Related Devices',
        3677 => 'Electronic Coils, Transformers & Other Inductors',
        3678 => 'Electronic Connectors',
        3679 => 'Electronic Components, NEC',
        3690 => 'Miscellaneous Electrical Machinery, Equipment & Supplies',
        3695 => 'Magnetic & Optical Recording Media',
        3711 => 'Motor Vehicles & Passenger Car Bodies',
        3713 => 'Truck & Bus Bodies',
        3714 => 'Motor Vehicle Parts & Accessories',
        3715 => 'Truck Trailers',
        3716 => 'Motor Homes',
        3720 => 'Aircraft & Parts',
        3721 => 'Aircraft',
        3724 => 'Aircraft Engines & Engine Parts',
        3728 => 'Aircraft Parts & Auxiliary Equipment, NEC',
        3730 => 'Ship & Boat Building & Repairing',
        3743 => 'Railroad Equipment',
        3751 => 'Motorcycles, Bicycles & Parts',
        3760 => 'Guided Missiles & Space Vehicles & Parts',
        3790 => 'Miscellaneous Transportation Equipment',
        3812 => 'Search, Detection, Navigation, Guidance, Aeronautical Sys',
        3821 => 'Laboratory Apparatus & Furniture',
        3822 => 'Auto Controls For Regulating Residential & Comml Environments',
        3823 => 'Industrial Instruments For Measurement, Display, and Control',
        3824 => 'Totalizing Fluid Meters & Counting Devices',
        3825 => 'Instruments For Meas & Testing of Electricity & Elec Signals',
        3826 => 'Laboratory Analytical Instruments',
        3827 => 'Optical Instruments & Lenses',
        3829 => 'Measuring & Controlling Devices, NEC',
        3841 => 'Surgical & Medical Instruments & Apparatus',
        3842 => 'Orthopedic, Prosthetic & Surgical Appliances & Supplies',
        3843 => 'Dental Equipment & Supplies',
        3844 => 'X-Ray Apparatus & Tubes & Related Irradiation Apparatus',
        3845 => 'Electromedical & Electrotherapeutic Apparatus',
        3851 => 'Ophthalmic Goods',
        3861 => 'Photographic Equipment & Supplies',
        3873 => 'Watches, Clocks, Clockwork Operated Devices/Parts',
        3910 => 'Jewelry, Silverware & Plated Ware',
        3911 => 'Jewelry, Precious Metal',
        3931 => 'Musical Instruments',
        3942 => 'Dolls & Stuffed Toys',
        3944 => 'Games, Toys & Children\'s Vehicles (No Dolls & Bicycles)',
        3949 => 'Sporting & Athletic Goods, NEC',
        3950 => 'Pens, Pencils & Other Artists\' Materials',
        3960 => 'Costume Jewelry & Novelties',
        3990 => 'Miscellaneous Manufacturing Industries',
        4011 => 'Railroads, Line-Haul Operating',
        4013 => 'Railroad Switching & Terminal Establishments',
        4100 => 'Local & Suburban Transit & Interurban Hwy Passenger Trans',
        4210 => 'Trucking & Courier Services (No Air)',
        4213 => 'Trucking (No Local)',
        4220 => 'Public Warehousing & Storage',
        4231 => 'Terminal Maintenance Facilities For Motor Freight Transport',
        4400 => 'Water Transportation',
        4412 => 'Deep Sea Foreign Transportation of Freight',
        4512 => 'Air Transportation, Scheduled',
        4513 => 'Air Courier Services',
        4522 => 'Air Transportation, Nonscheduled',
        4581 => 'Airports, Flying Fields & Airport Terminal Services',
        4610 => 'Pipe Lines (No Natural Gas)',
        4700 => 'Transportation Services',
        4731 => 'Arrangement of Transportation of Freight & Cargo',
        4812 => 'Radiotelephone Communications',
        4813 => 'Telephone Communications (No Radiotelephone)',
        4822 => 'Telegraph & Other Message Communications',
        4832 => 'Radio Broadcasting Stations',
        4833 => 'Television Broadcasting Stations',
        4841 => 'Cable & Other Pay Television Services',
        4899 => 'Communications Services, NEC',
        4900 => 'Electric, Gas & Sanitary Services',
        4911 => 'Electric Services',
        4922 => 'Natural Gas Transmission',
        4923 => 'Natural Gas Transmission & Distribution',
        4924 => 'Natural Gas Distribution',
        4931 => 'Electric & Other Services Combined',
        4932 => 'Gas & Other Services Combined',
        4941 => 'Water Supply',
        4950 => 'Sanitary Services',
        4953 => 'Refuse Systems',
        4955 => 'Hazardous Waste Management',
        4961 => 'Steam & Air-Conditioning Supply',
        4991 => 'Co-generation Services & Small Power Producers',
        5000 => 'Wholesale - Durable Goods',
        5010 => 'Wholesale - Motor Vehicles & Motor Vehicle Parts & Supplies',
        5013 => 'Wholesale - Motor Vehicle Supplies & New Parts',
        5020 => 'Wholesale - Furniture & Home Furnishings',
        5030 => 'Wholesale - Lumber & Other Construction Materials',
        5031 => 'Wholesale - Lumber, Plywood, Millwork & Wood Panels',
        5040 => 'Wholesale - Professional & Commercial Equipment & Supplies',
        5045 => 'Wholesale - Computers & Peripheral Equipment & Software',
        5047 => 'Wholesale - Medical, Dental & Hospital Equipment & Supplies',
        5050 => 'Wholesale - Metals & Minerals (No Petroleum)',
        5051 => 'Wholesale - Metals Service Centers & of fices',
        5063 => 'Wholesale - Electrical Apparatus & Equipment, Wiring Supplies',
        5064 => 'Wholesale - Electrical Appliances, Tv & Radio Sets',
        5065 => 'Wholesale - Electronic Parts & Equipment, NEC',
        5070 => 'Wholesale - Hardware & Plumbing & Heating Equipment & Supplies',
        5072 => 'Wholesale - Hardware',
        5080 => 'Wholesale - Machinery, Equipment & Supplies',
        5082 => 'Wholesale - Construction & Mining (No Petro) Machinery & Equip',
        5084 => 'Wholesale - Industrial Machinery & Equipment',
        5090 => 'Wholesale - Misc Durable Goods',
        5094 => 'Wholesale - Jewelry, Watches, Precious Stones & Metals',
        5099 => 'Wholesale - Durable Goods, NEC',
        5110 => 'Wholesale - Paper & Paper Products',
        5122 => 'Wholesale - Drugs, Proprietaries & Druggists\' Sundries',
        5130 => 'Wholesale - Apparel, Piece Goods & Notions',
        5140 => 'Wholesale - Groceries & Related Products',
        5141 => 'Wholesale - Groceries, General Line (merchandise)',
        5150 => 'Wholesale - Farm Product Raw Materials',
        5160 => 'Wholesale - Chemicals & Allied Products',
        5171 => 'Wholesale - Petroleum Bulk Stations & Terminals',
        5172 => 'Wholesale - Petroleum & Petroleum Products (No Bulk Stations)',
        5180 => 'Wholesale - Beer, Wine & Distilled Alcoholic Beverages',
        5190 => 'Wholesale - Miscellaneous Non-durable Goods',
        5200 => 'Retail - Building Materials, Hardware, Garden Supply',
        5211 => 'Retail - Lumber & Other Building Materials Dealers',
        5271 => 'Retail - Mobile Home Dealers',
        5311 => 'Retail - Department Stores',
        5331 => 'Retail - Variety Stores',
        5399 => 'Retail - Misc General Merchandise Stores',
        5400 => 'Retail - Food Stores',
        5411 => 'Retail - Grocery Stores',
        5412 => 'Retail - Convenience Stores',
        5500 => 'Retail - Auto Dealers & Gasoline Stations',
        5531 => 'Retail - Auto & Home Supply Stores',
        5600 => 'Retail - Apparel & Accessory Stores',
        5621 => 'Retail - Women\'s Clothing Stores',
        5651 => 'Retail - Family Clothing Stores',
        5661 => 'Retail - Shoe Stores',
        5700 => 'Retail - Home Furniture, Furnishings & Equipment Stores',
        5712 => 'Retail - Furniture Stores',
        5731 => 'Retail - Radio, Tv & Consumer Electronics Stores',
        5734 => 'Retail - Computer & Computer Software Stores',
        5735 => 'Retail - Record & Prerecorded Tape Stores',
        5810 => 'Retail - Eating & Drinking Places',
        5812 => 'Retail - Eating Places',
        5900 => 'Retail - Miscellaneous Retail',
        5912 => 'Retail - Drug Stores and Proprietary Stores',
        5940 => 'Retail - Miscellaneous Shopping Goods Stores',
        5944 => 'Retail - Jewelry Stores',
        5945 => 'Retail - Hobby, Toy & Game Shops',
        5960 => 'Retail - Nonstore Retailers',
        5961 => 'Retail - Catalog & Mail-Order Houses',
        5990 => 'Retail - Retail Stores, NEC',
        6021 => 'National Commercial Banks',
        6022 => 'State Commercial Banks',
        6029 => 'Commercial Banks, NEC',
        6035 => 'Savings Institution, Federally Chartered',
        6036 => 'Savings Institutions, Not Federally Chartered',
        6099 => 'Functions Related To Depository Banking, NEC',
        6111 => 'Federal & Federally Sponsored Credit Agencies',
        6141 => 'Personal Credit Institutions',
        6153 => 'Short-Term Business Credit Institutions',
        6159 => 'Miscellaneous Business Credit Institution',
        6162 => 'Mortgage Bankers & Loan Correspondents',
        6163 => 'Loan Brokers',
        6172 => 'Finance Lessors',
        6189 => 'Asset-Backed Securities',
        6199 => 'Finance Services',
        6200 => 'Security & Commodity Brokers, Dealers, Exchanges & Services',
        6211 => 'Security Brokers, Dealers & Flotation Companies',
        6221 => 'Commodity Contracts Brokers & Dealers',
        6282 => 'Investment Advice',
        6311 => 'Life Insurance',
        6321 => 'Accident & Health Insurance',
        6324 => 'Hospital & Medical Service Plans',
        6331 => 'Fire, Marine & Casualty Insurance',
        6351 => 'Surety Insurance',
        6361 => 'Title Insurance',
        6399 => 'Insurance Carriers, NEC',
        6411 => 'Insurance Agents, Brokers & Service',
        6500 => 'Real Estate',
        6510 => 'Real Estate Operators (No Developers) & Lessors',
        6512 => 'Operators of Nonresidential Buildings',
        6513 => 'Operators of Apartment Buildings',
        6519 => 'Lessors of Real Property, NEC',
        6531 => 'Real Estate Agents & Managers (For Others)',
        6532 => 'Real Estate Dealers (For Their Own Account)',
        6552 => 'Land Subdividers & Developers (No Cemeteries)',
        6770 => 'Blank Checks',
        6792 => 'Oil Royalty Traders',
        6794 => 'Patent Owners & Lessors',
        6795 => 'Mineral Royalty Traders',
        6798 => 'Real Estate Investment Trusts',
        6799 => 'Investors, NEC',
        7000 => 'Hotels, Rooming Houses, Camps & Other Lodging Places',
        7011 => 'Hotels & Motels',
        7200 => 'Services - Personal Services',
        7310 => 'Services - Advertising',
        7311 => 'Services - Advertising Agencies',
        7320 => 'Services - Consumer Credit Reporting, Collection Agencies',
        7330 => 'Services - Mailing, Reproduction, Commercial Art & Photography',
        7331 => 'Services - Direct Mail Advertising Services',
        7340 => 'Services - To Dwellings & Other Buildings',
        7350 => 'Services - Miscellaneous Equipment Rental & Leasing',
        7359 => 'Services - Equipment Rental & Leasing, NEC',
        7361 => 'Services - Employment Agencies',
        7363 => 'Services - Help Supply Services',
        7370 => 'Services - Computer Programming, Data Processing, Etc.',
        7371 => 'Services - Computer Programming Services',
        7372 => 'Services - Prepackaged Software',
        7373 => 'Services - Computer Integrated Systems Design',
        7374 => 'Services - Computer Processing & Data Preparation',
        7377 => 'Services - Computer Rental & Leasing',
        7380 => 'Services - Miscellaneous Business Services',
        7381 => 'Services - Detective, Guard & Armored Car Services',
        7384 => 'Services - Photofinishing Laboratories',
        7385 => 'Services - Telephone Interconnect Systems',
        7389 => 'Services - Business Services, NEC',
        7500 => 'Services - Automotive Repair, Services & Parking',
        7510 => 'Services - Auto Rental & Leasing (No Drivers)',
        7600 => 'Services - Miscellaneous Repair Services',
        7812 => 'Services - Motion Picture & Video Tape Production',
        7819 => 'Services - Allied To Motion Picture Production',
        7822 => 'Services - Motion Picture & Video Tape Distribution',
        7829 => 'Services - Allied To Motion Picture Distribution',
        7830 => 'Services - Motion Picture Theaters',
        7841 => 'Services - Video Tape Rental',
        7900 => 'Services - Amusement & Recreation Services',
        7948 => 'Services - Racing, Including Track Operation',
        7990 => 'Services - Miscellaneous Amusement & Recreation',
        7997 => 'Services - Membership Sports & Recreation Clubs',
        8000 => 'Services - Health Services',
        8011 => 'Services - Offices & Clinics of Doctors of Medicine',
        8050 => 'Services - Nursing & Personal Care Facilities',
        8051 => 'Services - Skilled Nursing Care Facilities',
        8060 => 'Services - Hospitals',
        8062 => 'Services - General Medical & Surgical Hospitals, NEC',
        8071 => 'Services - Medical Laboratories',
        8082 => 'Services - Home Health Care Services',
        8090 => 'Services - Misc Health & Allied Services, NEC',
        8093 => 'Services - Specialty Outpatient Facilities, NEC',
        8111 => 'Services - Legal Services',
        8200 => 'Services - Educational Services',
        8300 => 'Services - Social Services',
        8351 => 'Services - Child Day Care Services',
        8600 => 'Services - Membership or ganizations',
        8700 => 'Services - Engineering, Accounting, Research, Management',
        8711 => 'Services - Engineering Services',
        8731 => 'Services - Commercial Physical & Biological Research',
        8734 => 'Services - Testing Laboratories',
        8741 => 'Services - Management Services',
        8742 => 'Services - Management Consulting Services',
        8744 => 'Services - Facilities Support Management Services',
        8748 => 'Business Consulting Services, Not Elsewhere Classified',
        8880 => 'American Depositary Receipts',
        8888 => 'Foreign Governments',
        8900 => 'Services - Services, NEC',
        9721 => 'International Affairs',
        9995 => 'Non-Operating Establishments'
    );

    /**
     * Default value
     *
     * @var null|string
     */
    protected $_default = null;

    /**
     * Initialize the SIC code select box
     *
     * @return void
     */
    public function init()
    {
        $this->addMultiOption('', 'Choose One...');

        foreach ($this->_sics as $code => $description) {
            $this->addMultiOption($code, ($code . ' - ' . $description));
        }

        if (!empty($this->_default)) {
            $this->setValue($this->_default);
        }
    }
}
