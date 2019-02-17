Больше не развиваеться


## Synopsis

The project modifies Civilization 2 Multiplyer Gold Edition to include additional features and fixes.

## Requirement

Only the unmodified Civilization 2 Multiplyer Gold Edition v1.3 US version is supported

### Options

All of the fields in the `Options` section are feature toggles. To enable a feature, set the value to `1`. To disable a feature, set the value to `0`.

* `Log` When enabled, error messages will be written to the `civ2patch.log` file.
* `Music` When enabled, the application will look in the `Music` folder and play MP3 or OGG tracks. The name of the files must be in the format of `Track##.mp3` or `Track##.ogg`, where `##` is the 2 digits track number.
* `Multiplayer` When enabled, SDL will be used for networking instead of Winsock.
* `FixCpuUsage` When enabled, reduce CPU usage when the application is idle.
* `Fix64BitCompatibility` When enabled, patch the application to run on 64-bit Windows.
* `DisableCdCheck` When enabled, allow the application to run without the game CD.
* `DisableHostileAi` When enabled, AI will not be unreasonably hostile to the player.
* `SetRetirementYear` When enabled, allow the retirement year to be modified.
* `SetCombatAnimationFrameLength` When enabled, allow the combat animation speed to be modified.
* `SetPopulationLimit` When enabled, allow the population limit to be modified.
* `SetGoldLimit` When enabled, allow the gold limit to be modified.
* `SetMapTilesLimit` When enabled, allow the number of map tiles limit to be modified.