Project information
Currently, TOTPP contains the following patches (more information in the program itself):
City and unit limits: New, configurable limits up to 32,000. Fixes the home cities of all units to handle things like support, trade routes, etc.
Edit control fix: Fixes a crash on 64-bit operating systems when an edit control is created.
Enable throne room: Enables building of throne room improvements. This feature was never removed from TOT, just disabled.
City/unit overview: Displays the number of cities and units for the player's tribe in the empire overview window.

Technical Information
TOTPP consists of a launcher and a DLL. The launcher has a GUI that allows the user to select which patches to apply (see screenshot). After patch selection, it launches TOT and injects the DLL into the process. The DLL then proceeds to patch TOT in memory.

Requirements
TOTPP requires at least Test Of Time v1.1 to run. It does not check your version, so if you can't get it to run correctly, try a vanilla v1.1 executable. TOTPP is compatible with many user-supplied patches. It is however not compatible with Mastermind's patch, so if you patched your game with it, be sure to disable the Edit control patch in the launcher.

Installation
Extract TOTLauncher.exe and TOTPP.dll to the Test Of Time installation folder (containing civ2.exe) and run TOTLauncher.exe. Adding /q on the command line will make it go straight to the game with your previous settings.

This software is provided as-is, without warranty of any kind. You may redistribute it as long as you give credit. Because of some of the techniques used in TOTPP (DLL injection and in-memory patching), you may get alerts from your anti-virus software. These are false positives. However, running random executables from the internet is always a bad idea, so be careful and test it in a virtual machine until you're sure it does what it advertises!

Since this is the first release, there's bound to be bugs and issues. Please feel free to post them here. I'd also welcome any feedback on the project. In the meantime, enjoy!