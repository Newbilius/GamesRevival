Автолут, автоснятие шлема вне-боя, авто-взлом замков напарником и пр. (мод модульный)


Fixes for various bugs separated into single fixes for those who want to pick & choose which fix to apply.

---LATEST UPDATE---

Version 1.99 has been uploaded. This is a BETA version. All components have been through ALPHA testing and have been found to work.

This is still a BETA release and is not for the non-adventurous type who don't want to deal with bugs. Wait for the final Version 2.0 release if you are!

PLEASE REMEMBER TO COMPLETELY REMOVE ANY OLD VERSIONS WHEN INSTALLING A NEW VERSION

Some key components of this version include:
Shale's Rock Barrage is working now!
New tweak component to disable ALL modal ability graphics! Compatible with almost everything out there!
Automatically apply party buffs to new summons and resurrected party members!
Item set bonuses apply immediately when party members are swapped in! No need re-equip!
Autolooting of randomly generated treasure without bringing up loot UI!
Party Rogues will automatically help out with lockpicking & disarming trap after you fail!


PLEASE REMEMBER TO COMPLETELY REMOVE ANY OLD VERSIONS WHEN INSTALLING A NEW VERSION

This is a compilation of various bug fixes for the game that deals with bugs in the script. It is primarily intended for those who want to play the 'pure' game without major tweaks but also enjoy bug fixes that are included in those tweak packages.

These fixes have only been tested against a pure standard version 1.04 build. The majority of fixes either use EventManager or modifies 2da files and leaves the major game scripts alone, thus they _should_ be compatible with most other mods that use EventManager, but no guarantees.

INSTALLATION:

Each component can be installed separately. Just copy the folder into your override directory, which for most installs will be at:

/My Documents/BioWare/Dragon Age/packages/core/override

* Core Files
This folder contains the base code REQUIRED for any other component to work. The entirety of this folder should be copied into your override folder as per installation instructions.

COMPATIBILITY:

There is a game imposed limitation which can cause things to break if you have too many mods all affecting the same event. In particular, cutscenes & plot events such as the "Siege of Redcliffe" can fail to execute properly. All the components from this pack will co-exist with each other without introducing such bugs, but any additional mod can cause problems. 

This fixpack utilizes a modifed version of Anakin5's Event Manager (http://social.bioware.com/project/1907/) designed to work within this limitation. However, for it to work properly, there must be no other mod that contains any file with the name "engineevents_*.gda" installed directly into /override. Mods installed as proper addins (via .dazip) are fine and will not break this pack.

FIXES:

* Fix - Ability Weapon Requirements
Fixes the bug where some abilities that require melee weapons remain active when ranged weapons are swapped in.

* Fix - Arrow of Slaying Level Based Damage
Bonus damage for Arrow of Slaying was not coded accurately as (level difference)^2, but rather as a bitwise XOR with 2. This fixes it so that the level based bonus damage is once again the square of the level difference on targets of lower level.

* Fix - Blood Control Damage on Resist
Enemies that resist blood control will now properly suffer a DoT. Enemies that get controlled no longer get a DoT as well. Some enemies have dialogue attached and automatically resist the spell.

* Fix - No Crushing Prison Friendly Target
Fixes Crushing Prison so you can no longer target friendlies with it.

* Fix - Curse of Mortality Combat Health Regen
Fixes the bug where Curse of Mortality was not properly preventing health regen during combat.

* Fix - Destroyer Debuff on Normal Hits
Fixes the bug where the destroyer debuff was only applying on critical hits and not on normal hits.

* Fix - Dual Wield Expert with Other Dots
Fixes the bug where Dual Wield Expert would not apply its effect is another DoT was present on the target.

* Fix - Find Vitals
Fixes Find Vitals to properly apply its passive bonus

* Fix - Force Field Blocks Drain
Fixes the bug where you can drain health from targets with force field via Drain Life or Blood Sacrifice, even though you cause no damage. Now there is no draining of health to accompany the lack of damage.

* Fix - Gear Minimum Stat Check
Fixes the bug where you can continue to wear gear even if you no longer meet the minimum stat requirements. Unequippable items will be removed after you close the inventory screen. You may edit the color and duration of the message displayed when gear is removed by editting "gtfixpack_gmsc1224.gda" with GDApp. In the "Active" column, you can enter in hexcode the duration in seconds, followed by the hexcode for the desired color. The default setting is 0x3FF0000, or Red for 3 seconds.

* Fix - Haste Ranged Aim
Fixes the bug where haste will add 0.8s to your aim time instead of subtracting 0.2s.

* Fix - Haste Weapon Speed
Fixes the bug where if you drop below 0.5s weapon speed, it will reset to 1.0s.

* Fix - Healing Received
Fixes the bug where healing received was not properly initialized on characters, thus the items had no effect. This fix will apply to your character whenever you equip any items with the +healing received property.

* Fix - Increases Monetary Gain
Fixes monetary gain items to properly give additional 5% gold from killing monsters. This fix has a peculiar behavior as described below.

1. Does NOT affect gold from chests or stealing. Such a fix would alter core game scripts beyond the scope of this pack.
2. Does NOT stack with itself on the same character.
3. Does NOT stack with XP bonus from Archivists Sash on the same character.
4. DOES stack with similar properties on different characters. If your PC has the Archivist's Sash + 2 Monetary Gain items, none would stack and only the latest equipped applies. If your PC has the Archivist's Sash, an NPC has Guildmaster's Belt, and another has Pearl of the Anoited, you would get +25XP from Codex and 10% additional gold from monsters.

2, 3, and 4 may not make much sense, but it is how the framework for these 'reward bonus' properties were coded by Bioware, and as such the behavior is retained. 'Reward Bonus' are coded as effects, and not attributes, and thus do not stack with each other on the same character. This fix merely makes monster/stealing gold read off the correct effect.


* Fix - Item Set Bonus on Party Change
Fixes the bug where party members would not have item set bonus applied when added to the party.

* Fix - Legion of the Dead Heraldry
Fixes Legion of the Dead Heraldry to give +3 stats like all other heraldries.

*Fix - Magebane/Soldierbane Removes Mana/Stamina
Fixes these poisons to correctly remove mana/stamina instead of adding it.

* Fix - Mana Cleanse Removes Mana
Fixes the bug where Mana Cleanse was adding mana instead of removing mana.

* Fix - Mighty Blow Slows
Fixes the bug where Might Blow wasn't applying a slow effect. Now slows by 50% for 3s (as per a level 0 Slow Rune).

* Fix - Monster Canine Howl Defense Penalty
Fixes the bug where enemy canine monsters' howl did not apply a defense penalty.

* Fix - No Stacking Poisons & Coatings
Fixes the bug where you could stack more than one poison and one coating on your weapon.

* Fix - No Stealth Drop on Friendly Spells
Fiexes the bug where stealth would drop on entering friendly spells.

* Fix - Party Buffs on Resurrection
Fixes the bug where party members who resurrect after dying would not receive existing party buffs. This fix effectively causes your party to deactivate and re-activate party buffs instantly when a party member is resurrected. The corresponding buffs will be applied immediately, but will also start the appropriate cooldown. Buffs checked for are: Haste, Frost/Flaming/Telek Weapon, Song of Valor/Courage.

* Fix - Party Buffs on Summon
Fixes the bug where summons would not receive existing party buffs. This fix effectively causes your party to deactivate and re-activate party buffs instantly when a summon is cast. The corresponding buffs will be applied immediately, but will also start the appropriate cooldown. Buffs checked for are: Haste, Frost/Flaming/Telek Weapon, Song of Valor/Courage.

* Fix - PC Base Crit Chance
Fixes the bug where the PC had 0% base crit chance, instead of 3%. Fix is applied whenever the PC equips a weapon in the main hand.

* Fix - Power of Blood Dual Wield Expert
Fixes the bug where the blowback damage from Power of Blood modals for warrior & rogue were causing Dual Wield Expert DoT to apply.

* Fix - Repeater Gloves Proper Rapid Aim
Fixes Repeater Gloves (from Return to Ostagar DLC) to properly gives -0.3s to your aim time, instead of -3.0s.

* Fix - Shale Rock Barrage
Shale's Rock Barrage now functions properly again. She will throw rocks up into the air, then slam her fists together causing them to start falling down.

* Fix - Shattering Blow
Fixes Shattering Blow to properly give damage bonus against constructs (golems, skeletons, corpses, sylvans)

* Fix - Shield Wall Reduces Damage
Fixes the bug where Shield Wall was not reducing damage. Now reduces weapon damage by 20%. Armor penetration, enchantmant damage, and non-weapon damage is not affected. Note that your character sheet damage numbers may not go down by 20%, as that figure is a guesstimate based on weapon speed buffs.

* Fix - Swift Salve Ranged Aim
Fixes the bug where swift salve will add 0.9s to your aim time instead of subtracting 0.1s.

* Fix - Weaken Nearby Darkspawn
Fixes the bug where the Weaken Nearby Darkspawn property was not functioning. Now causes darkspawn within 2.5m to be penalized -5 attack and -5 defense.

TWEAKS

* Tweak - Aim Accuracy Stacking
Aim doubles the critical acquired from Accuracy, so activating Accuracy then Aim is vastly different from vice versa. This tweaks Aim to not double the bonus from Accuracy, but only the pre-Accuracy crit chance.

* Tweak - Auto Loot
Automatically loot without bringing up the inventory screen. This tweak will not affect chests that have no randomly generated treasure, nor will it autoloot plot items. This should help avoid potential plot/quest related problems. Also will only autoloot when the Main Character loots; followers will bring up the inventory screen as normal.

* Tweak - Auto Loot Kills
Automatically loot mobs when they die. Will only autoloot when the mob dies while the hero is currently being controlled.

* Tweak - Chain Lightning Propogates After Kill
Tweaks Chain Lightning to continue chaining even if it kills its target. However, if the target dies before the bolt arrives, there will be no chaining (No chaining off of corpses).

* Tweak - Display Received Items
Display the named of received items above the character's head. It is possible to change the color and duration of the message by editting "gtfixpack_dri1216.gda" using GDApp or the toolset. In the "Active" cell, you can enter in hexcode the duration followed by the color code. For example, the default settings is 3 seconds, with color code 00FF00 (bright green), thus you would enter 0x300FF00. This converts into the normal number of 50396928. To change it to display for 5 seconds, you would use 0x500FF00, or 83951360. To change it to display for 5 seconds in darker green, you would use 0x500CC00, or 83938304. The first number cannot exceed F, or 15 seconds. 

* Tweak - Force Field Reduces Threat
Creatures that cannot attack their target due to being out of range or not having line of sight reduces threat on that target by 75%. This tweaks applies the same reduction if the target has a Force Field.

* Tweak - Helpful Party Members
Party rogues will follow up on their words by actually unlocking/disarming chests & traps after making their helpful comments when the PC fails.

* Tweak - Hostility Affecting Items
Despite what many believe, existing coding indicates that items with "Reduces Hostility" are intended to speed up the decay of threat, not reduce the generation of threat. However, the decay appears to be hard coded into the engine at -0.5 threat per second, and cannot be affected by scripts. This tweak makes it so that the "Reduces Hostility" reduces threat from damage by 5% for each power level, and "Increases Hostility and Intimidation" increases threat from damage by 5% for each power level. Different hostility affecting items have differing power levels. Note that the "Increases Hostility" (without Intimidation) property on items like Cadash Stompers are currently functional and add 5 points of threat to each attack.

* Tweak - Messy Kills Adds Gore
Changes the "Messy Kills" item property to have a chance to add more gore to the target being hit. While it seems likely that the property is intended to increase the chance to perform a Deathblow, such a fix overlaps with Fix - Haste Weapon Speed and cannot be decoupled, thus this alternative tweak.

* Tweak - Misdirection Backstab to Hits
Changes Misdirection Hex to convert backstabs to hits if the backstab would have been a critical.

* Tweak - Nature Damage Spells per Description
Changes Stonefist & Walking Bomb DOTs to be nature damage. Walking Bomb explosion remain physical damage (being hit by flying body parts).

* Tweak - No Deactivation on Weapon Swaps
Modal abilities that have weapon requirements instantly reactivate after deactivating upon a weapon swap

* Tweak - No Helmet
Helmets will be removed during exploration and re-equipped during combat. The last helmet you put on will be 'remembered.' To cause a new helmet to be remembered, simply equip another helmet.

* Tweak - No Power of Blood Damage While Exploring
Disables Tainted Blood self-damage while exploring to stop the annoying 'head bob' animation. This will also standardize the spells between Origin & Awakening to do the same self damage in both campaigns.

* Tweak - Scattershot Shatters After Kill
Tweaks scattershot so it shatters even if the initial shot kills the target. If the target dies before the initial shot lands, it will not shatter.

* Tweak - XP on All Deaths
Gives the party XP when monsters kill other monsters.

* Tweak - Reapply Buffs on Explore
Certain modals are turned off when you leave combat mode. This tweak re-applies thems.

* Tweak - Remove Modal VFX
This tweak functions very similarly to the widely popular PAR by removing the graphics from modal abilities. All modal abilities are affected, including abilities from Awakening, DLCs, and any custom abilities. You may change this duration by editting "gtfixpack_rmvfx1214.gda" with GDApp. Set the "Active" column for how long you want VFX to be displayed. The minimum setting is 1 second, as a value of 0 will disable this component. The default value is 3 seconds.

* Tweak - Remove Modal VFX Ground AoE
This tweak removes the ground effect AoE from modal abilities like Rally. Abilities that leave you stationary, such as One with Nature, Captivate, and Shale's Aura are not affected. All AoE modal abilities are affected, including abilities from Awakening, DLCs, and any custom abilities. You may change this duration by editting "gtfixpack_rmvfxa1215.gda" with GDApp. Set the "Active" column for how long you want VFX to be displayed. The minimum setting is 1 second, as a value of 0 will disable this component. The default value is 3 seconds.

* Tweak - Remove Rally & Air of Insolence Sounds
Removes all sounds from these two abilities.

* Tweak - Slow Rune Rank Affects Duration
Changes SLow Rune such that the rank of the rune affects duration of the slow effect, as per Paralyze Runes. Duration is 3s + 2s per rune rank. Proc chance remains at 10%, slow effect remains 50% movement speed.

* Tweak - Standardize Crit Interactions
Standardizes the interaction between the various always-crit/never-crit abilities. Now, each will negate the other on a one for one basis. So if you have 2 always-crit abilities and 1 never-crit ability, you will always crit. If you have 1 of each, you will attack as normal.

Alway Crit: Attacking out of stealth, Death Hex, Autocrit mob effect
Never Crit: Double Strike, Rapid Shot



Version history:

v1.99.13: BETA
Minor text update on message for gear removed for insufficient attributes
Fixed Love Letters giving double messages
Displays stack size for items received
Autolootkills no longer breaks quests that check for deaths
Added tweak to remove helmet on exploration & re-equip on combat
Scattershot/Chain Lightning chaining when intial target is killed reclassified as tweaks based on in-code comments
Added tweak to remove helmet during exploration and re-equip during combat

v1.99.12: BETA
Added fix to prevent friendly targetting with crushing prison
Added fix to prevent stealth dropping from entering friendly spells
Added tweak to standarize inteaction between various always/never crit abilities
Added tweak to misdirection hex to turn backstabs into normal hits instead off misses if it would have critted
Added ability to customize items received floaty text duration (read description!)
Autolootkills further adjusted to work better with deathblows
Autolootkills only applies if the hero is being controlled when the mob dies
Items with on acquire events will no longer get autolooted
Aura of Pain now works with remove ground aoe vfx
Changed gear min stat check to happen after inventory screen is closed
Added fix to melee-only abilities staying active after swapping to ranged weapon
Added tweak to reactivate abilities that deactivate after weapon swap, but are still valid under the new weapon set
Autoloot changed to not filter normal Awakening chests
Additional cleanup when removing items with Weaken Nearby Darkspawn
Additional compatibility with Random Age
Additional performance tweaks with VFX removers
Added ability to customize duration VFX are shown before being removed (read description!)
Additional compatibility with Random Age

v1.99.11: BETA
Adjustment to XP on All Death to further remove extranaeous XP gain
Fixed helpers not helping to disarm traps
Autoloot filtered for DLC chests
Autolootkills filtered out for mobs that explode/dissipate on death, and items that are designated undroppable 
Autolootkills works better around deathblows
Added ability to customize items received floaty text color (read description!)
No longer get material type when receiving Shale crystals
Added fix to dual wield expert to apply the DoT even if the target already has any other DoT effect.

v1.99.10: BETA
Autoloot filters for items with dialogue (armor stand in Captured!)
Reactivate after combat buffs lose VFX if the appropriate module is active
Added Fix to apply destroyer debuff on normal hits as well as crits
Clothings & other items with no base stat requirements no longer affected by gear minimum stat check

v1.99.9: BETA
Fixed incompatibility with Advanced Tactics
Added tweak to reactivate buffs that deactivate on leaving combat mode
Removed party buffs triggering cooldown after reapplying on summon/resurrection/party change

v1.99.8: BETA
Fixed rally bonuses stacking bug

v1.99.7: BETA
Fixed Autoloot Kills & XP on All Deaths to avoid inactive mobs
Gear stat check no longer applies to items with no requirements
Added compatibility with Advanced Tactics for XP on All Death module

v1.99.6: BETA
Display received items now shows stack size & material
Reverted auto loot to ignore rank (practically everything in awakening is tagged boss rank), filtered for plot chests
Itemsets update after selecting party and leaving camp
Added fix to check equipment for minimum requirements on attribute changes
Added fix to re-apply party buffs when you change your party
Bash locks removed
Further filters for auto loot kills to remove weird items like staff bullets
Further changes to work around event redirection limit at the expense of compatibility
Power of Blood no longer affected by Spirit Warrior

v1.99.5: BETA
Fixed abilities not functioning 
Added tweak to bash locks & doors - NOT FUNCTIONING YET

v1.99.4: BETA
Added summon bronto (from golems of am) to party buffs on summon tweak
Autoloot stop looting BOSS/ELITE BOSS level chests
Fixes issue with too many eventmanager mods breaking cutscene. Adding too many still will break, but it's much more forgiving now

v1.99.3: BETA
Included recompiled correct script for Haste weapon speed fix

v1.99.2: BETA
Fixed tweak - no power of blood damage while exploring to not break cutscenes
Fixed tweak - remove modal vfx ground aoe

v1.99.1: BETA
Re-added Fix - Arrow of Slaying Level Based Damage

v1.99.0: BETA release of version 2
Included modified Event Manager
Added tweak for Slow Rune Rank Affects Duration
Added fix for Magebane/Soldier's Bane Removing Mana/Stamina
Fix - Weaken Nearby Darkspawns now has proper effect icon & text on the wielder
Fixed bug with "Tweak - Hostility Affecting Items" not letting plot creatures die
Added fix for Party Buffs re-applying on resurrection
Changed Swift Salve & Haste Fixes to reduce aim time by 0.1s & 0.2s respectively.
Added fix for enemy canine howls not penalizing defense
Added fix for Mighty Blow applying slow effect
Added fix for Blood Control Damage on Resist
Added tweak for Force Field reducing threat
Tweak - Messy Kills Adds Gore fixed
Added fix for Shale's Rock Barrage properly working again
Added fix for PC getting 3% base crit chance
Added fix to prevent health drains on target with force field
Added tweak to remove graphics from modal abilities
Added tweak to remove sound from Rally & Air of Insolence
Added tweak to remove AoE ground effects from modal abilities
Added fix for find vitals
Added tweak to display name of received items
Added fix for item set bonus on party change
Added fix for shield wall reducing damage
Added auto loot tweak
Added auto loot on kill tweakAdded tweak to give XP for mobs killing mobs
Added tweak to get party members to help unlock/disarm
Added fix for poison/coating stacking

v1.3.1:
Fixed bug with "Tweak - Hostility Affecting Items" not letting plot creatures die
Disabled "Tweak - Messy Kills Adds Gore" until further revision

v1.3: 
Added fix for Increase Monetary Gain
Added fix for Legion of the Dead Heraldry
Added fix for Shattering Blows
Added fix for Weaken Nearby Darkspawns
Added tweak for Hostility Affecting Items
Added tweak for Messy Kills Adds Gore

v1.2:
Added arrow of slaying, curse of mortality, mana cleanse fixes.
Added +healing received fix.
Expanded Dual Wield Expert Fix to Tainted Blade and Blood Thirst.

v1.1.1: Fixed 'Tainted Blade Dual Wield Expert Fix' bug during Anvil of Void spirits.
v1.1:
Updated fixes to use EventManager or ABI_ 2DA files instead of recompiling core scripts.
Added no tainted blood damage while exploring tweak.
Added accuracy & aim stacking tweak.

v1.0: Initial release